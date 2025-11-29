<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function form()
    {
        return view('auth.cambiar-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->debe_cambiar_password = false;
        $user->save();

        return redirect()->route('home')->with('success', 'Contraseña actualizada correctamente.');
    }
}
