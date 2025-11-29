<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//agregamos lo siguiente
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuariosExport; 


use Illuminate\Support\Facades\Auth;

use Laravolt\Avatar\Facade as Avatar; 

class UsuarioController extends Controller
{

    public function exportar(Request $request)
    {
        $estado = $request->input('estado');
        $rol = $request->input('rol');

        return Excel::download(new UsuariosExport($estado, $rol), 'usuarios.xlsx');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    
    {
        $query = User::query();

        User::where('estado', 'activo')
        ->whereDate('fecha_fin', '<=', Carbon::today())
        ->update(['estado' => 'inactivo']);

        // Filtro por estado (activo/inactivo)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por rol usando Spatie
        if ($request->filled('rol')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->rol);
            });
        }

        //Buscador 
    
        if ($request->filled('busqueda')) {
            $query->where('name', 'LIKE', '%' . $request->busqueda . '%');
        }


        // Paginación
        $usuarios = $query->paginate(5)->appends($request->query());

        // Lista de roles para el select de filtro
        $roles = \Spatie\Permission\Models\Role::pluck('name', 'name')->all();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //aqui trabajamos con name de las tablas de users
        $roles = Role::pluck('name','name')->all();
        return view('usuarios.crear',compact('roles'))->with('success', 'Usuario creado correctamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|same:confirm-password',
        'roles' => 'required',
        'fecha_inicio' => 'nullable|date',
        'fecha_fin' => 'nullable|date',
        
    ]);

    $input = $request->all();
    $input['password'] = Hash::make($input['password']);
    $input['debe_cambiar_password'] = 1;

    $usuario = User::create($input);
    $usuario->assignRole($request->input('roles'));

    session()->flash('success', 'Usuario creado correctamente.');
    return redirect()->route('usuarios.index');
}

    public function show($id)
    {
        $usuario = User::findOrFail($id);
        $roles = $usuario->getRoleNames(); // Si quieres mostrar roles en detalle

        $avatarBase64 = Avatar::create($usuario->name)->toBase64();

        return view('usuarios.show', compact('usuario', 'roles'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    public function edit($id)
    {
        $usuario = User::findOrFail($id); 

        $roles = Role::pluck('name','name')->all();
        $userRole = $usuario->roles->pluck('name','name')->all();

        return view('usuarios.editar', compact('usuario', 'roles', 'userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|nullable|same:confirm-password',
            'roles' => 'required',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'estado' => 'required|in:activo,inactivo',
        ]);
    
        $usuario = User::findOrFail($id);
    
        // Guardar roles anteriores antes de eliminarlos
        $rolesAntes = $usuario->roles->pluck('name')->toArray();
    
        $input = $request->except(['password']);
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }
    
        $usuario->update($input);
    
        // Eliminar roles existentes y asignar los nuevos
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $usuario->assignRole($request->input('roles'));
    
        // Comparar roles anteriores y nuevos
        $rolesDespues = $request->input('roles');
        if (array_diff($rolesAntes, $rolesDespues) || array_diff($rolesDespues, $rolesAntes)) {
            // Registrar el cambio de rol en la auditoría
            \App\Models\Audit::create([
                'user_id' => auth()->id(),
                'action' => 'actualizó',
                'model_type' => User::class,
                'model_id' => $usuario->id,
                'changes' => json_encode([
                    'rol' => [implode(', ', $rolesAntes), implode(', ', $rolesDespues)]
                ])
            ]);
        }
    
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            
            // Forzar cambio de contraseña solo si la edita alguien más (admin o super admin)
            if (auth()->user()->id !== $usuario->id &&
                (auth()->user()->hasRole('Super_Administrador') || auth()->user()->hasRole('Administrador'))) {
                $usuario->debe_cambiar_password = true;
            }
        }
        
        $usuario->save();
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }




}
