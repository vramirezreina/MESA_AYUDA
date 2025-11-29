<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketHistorial;
use App\Models\HistorialTicket;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;
use App\Mail\TicketAsignado;
use App\Mail\TicketEstadoActualizado;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport; 

use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-ticket|crear-ticket|editar-ticket|asignar-ticket')->only('index');
        $this->middleware('permission:crear-ticket', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-ticket', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asignar-ticket', ['only' => ['asignar']]);
    }

    public function exportar(Request $request)
{
    return Excel::download(
        new TicketsExport(
            $request->creador,
            $request->estado,
            $request->tipo,
            $request->rango
        ),
        'tickets.xlsx'
    );
}

public function index(Request $request)
{
    $user = auth()->user();

    $query = Ticket::with(['creador', 'asignado'])->orderBy('created_at', 'desc');

    if ($user->hasAnyRole(['Administrador', 'Super_Administrador'])) {
        // No hay filtro adicional, puede ver todos los tickets
    } else {
        $query->where(function ($subQuery) use ($user) {
            $subQuery->where('usuario_creador_id', $user->id)
                     ->orWhere('usuario_asignado_id', $user->id);
        });
    }

    // Aplicar filtros
    if ($request->filled('creador')) {
        $query->where('usuario_creador_id', $request->creador);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('rango')) {
        $fechaInicio = now();
        switch ($request->rango) {
            case 'mensual':
                $fechaInicio->subMonth();
                break;
            case 'bimensual':
                $fechaInicio->subMonths(2);
                break;
            case 'trimestral':
                $fechaInicio->subMonths(3);
                break;
            case 'semestral':
                $fechaInicio->subMonths(6);
                break;
        }

        $query->where('created_at', '>=', $fechaInicio);
    }

    $tickets = $query->paginate(10);
    $soportes = User::role('soporte')->get();
    $usuarios = User::all();

    return view('tickets.index', compact('tickets', 'soportes', 'usuarios'));
}

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $existe = Ticket::where('usuario_creador_id', Auth::id())
    ->where('descripcion', $request->descripcion)
    ->where('created_at', '>=', now()->subMinutes(1))
    ->exists();

if ($existe) {
    return redirect()->route('tickets.index')->with('error', 'Ya enviaste un ticket igual hace poco.');
}


        $archivoPath = null;

        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('tickets', 'public');
        }

        $request->validate([
            'tipo' => 'required',
            'descripcion' => 'required',
            'archivo' => 'nullable|file',
        ]);


        $ticket = Ticket::create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'archivo' => $archivoPath,
            'usuario_creador_id' => Auth::id(),
            'estado' => 'abierto',
            'token_valoracion' => Str::uuid(), 
        ]);

        TicketHistorial::create([
            'ticket_id' => $ticket->id,
            'evento' => 'enviado',
            'detalle' => 'Ticket creado por ' . Auth::user()->name,
        ]);

        $ticket->load('creador');

        


        // Envía el correo al administrador
    Mail::to('sistemas.tic@infihuila.gov.co')->send(new TicketCreated($ticket));

    $destinatario = $ticket->creador;

        if ($destinatario && $destinatario->email) {
            Mail::to($destinatario->email)->send(new TicketEstadoActualizado($ticket, $destinatario));
        }



    return redirect()->route('tickets.index')->with('success', 'Ticket enviado y notificado');
       
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['creador', 'asignado', 'historial']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'estado' => 'required|in:abierto,pendiente,resuelto,cerrado',
            'comentario_soporte' => 'nullable|string|max:1000',
        ]);

        $estadoAnterior = $ticket->estado;
        $ticket->update($request->all());
   
        if (auth()->user()->hasAnyRole(['Soporte', 'Administrador', 'Super_Administrador'])) {
            $ticket->comentario_soporte = $request->comentario_soporte;
        }

        if ($estadoAnterior !== $request->estado && $request->estado === 'resuelto') {
            TicketHistorial::create([
                'ticket_id' => $ticket->id,
                'evento' => 'resuelto',
                'detalle' => 'Marcado como resuelto por ' . Auth::user()->name,
            ]);
        }

        HistorialTicket::create([
            'ticket_id' => $ticket->id,
            'evento' => 'estado cambiado',
            'detalle' => 'Estado cambiado de ' . $estadoAnterior . ' a ' . $ticket->estado,
            'usuario_id' => auth()->id(),
        ]);

       
    $destinatario = $ticket->creador;
    if ($destinatario && $destinatario->email) {
        Mail::to($destinatario->email)->send(new TicketEstadoActualizado($ticket, $destinatario));
    }

    
        return redirect()->route('tickets.index')->with('success', 'Ticket actualizado.');
    }

    public function asignar(Request $request, Ticket $ticket)
    {
        $request->validate([
            'usuario_asignado_id' => 'required|exists:users,id',
        ]);

        $nuevoId = $request->usuario_asignado_id;
        $asignadoAntes = $ticket->usuario_asignado_id;

        $ticket->update(['usuario_asignado_id' => $nuevoId]);

        $usuarioAsignado = User::find($nuevoId);

        TicketHistorial::create([
            'ticket_id' => $ticket->id,
            'evento' => $asignadoAntes ? 'reasignado' : 'asignado',
            'detalle' => ($asignadoAntes ? 'Reasignado' : 'Asignado') . ' a ' . $usuarioAsignado->name,
        ]);

        Mail::to($usuarioAsignado->email)->send(new TicketAsignado($ticket));

        return redirect()->route('tickets.index')->with('success', 'Ticket asignado y notificado.');
    }

    public function auditoria(Ticket $ticket)
    {
        $auditorias = $ticket->auditorias()->with('usuario')->orderByDesc('fecha_hora')->get();
    
        return view('tickets.auditoria_ticket', compact('ticket', 'auditorias'));
    }
    

    public function updateComentario(Request $request, Ticket $ticket)
    {
        if (!auth()->user()->hasAnyRole(['Soporte', 'Administrador', 'Super_Administrador'])) {
            abort(403);
        }

        $request->validate([
            'comentario_soporte' => 'nullable|string|max:1000',
        ]);

        $ticket->comentario_soporte = $request->comentario_soporte;
        $ticket->save();

        return back()->with('success', 'Comentario actualizado');
    }



}

