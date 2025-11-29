<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Audit;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
    
        $conteoRoles = [];
        $ticketsLabels = [];
        $ticketsData = [];
        $actividadModulos = [];
    
        if ($user->hasRole('Administrador|Super_Administrador')) {
    
            // 1. Usuarios por rol
            $usuarios = User::with('roles')->get();
            foreach ($usuarios as $usuario) {
                foreach ($usuario->roles as $rol) {
                    $conteoRoles[$rol->name] = ($conteoRoles[$rol->name] ?? 0) + 1;
                }
            }
    
            // 2. Tickets por día
            $ticketsPorDia = Ticket::selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->pluck('total', 'fecha');
    
            $ticketsLabels = $ticketsPorDia->keys();
            $ticketsData = $ticketsPorDia->values();
    
            // 3. Actividad por módulo
            $actividadModulos = [
                'Usuarios' => User::count(),
                'Roles' => Role::count(),
                'Auditorías' => Audit::count(),
                'Tickets' => Ticket::count(),
            ];
        }
    
        return view('home', [
            'rolesData' => json_encode($conteoRoles),
            'ticketsLabels' => json_encode($ticketsLabels),
            'ticketsData' => json_encode($ticketsData),
            'actividadModulos' => json_encode($actividadModulos),
        ]);
    }
    

}
