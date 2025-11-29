<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginCifradoController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'payload' => 'required|string',
            'iv' => 'required|string',
        ]);

        $aesKey = session('aes_key');
        if (!$aesKey) {
            return response()->json(['message' => 'Clave AES no encontrada'], 400);
        }

        $iv = base64_decode($request->iv);
        $encrypted = base64_decode($request->payload);

        $decrypted = openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            base64_decode($aesKey),
            OPENSSL_RAW_DATA,
            $iv
        );

        $credentials = json_decode($decrypted, true);

        if (!$credentials || !isset($credentials['email'], $credentials['password'])) {
            return response()->json(['message' => 'Credenciales inválidas'], 422);
        }

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ])) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => '/home'
            ]);
        }

        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    
}
