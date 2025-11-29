<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon; 

class LoginController extends Controller
{
    use AuthenticatesUsers;

 
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password
        ];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Si la fecha fin es hoy o anterior, se inactiva
        if ($user->fecha_fin && Carbon::parse($user->fecha_fin)->isSameDay(now())) {
            $user->estado = 'inactivo';
            $user->save();
    
            \Auth::logout(); // cerrar sesión del usuario
    
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido inactivada porque llegó la fecha de finalización.',
            ]);
        }
    
        // Validar si ya está inactivo (incluso por fecha pasada)
        if ($user->estado === 'inactivo') {
            \Auth::logout();
    
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ]);
        }
    
        // Si todo está bien y no debe cambiar contraseña
        if ($user->debe_cambiar_password) {
            return redirect()->route('password.cambiar');
        }
    
        return redirect()->intended($this->redirectPath());
    }
    



}
