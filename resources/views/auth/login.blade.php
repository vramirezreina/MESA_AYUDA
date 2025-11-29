@extends('layouts.auth_app')
@section('title', 'Iniciar Sesión')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="card">
    <div class="card-header"><h4 style="color: #007080">Iniciar Sesión</h4></div>
    <div class="card-body">
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Ingresar correo electrónico" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingresar Contraseña" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                        <img src="https://svgsilh.com/svg_v2/1915455.svg" width="20px">
                    </button>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-lg btn-block" style="background: #c6d219; color: white;">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CryptoJS y lógica de cifrado -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
<script>
    let aesKey = null;

    async function getAesKey() {
        const res = await fetch('/api/get-aes-session-key', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await res.json();
        aesKey = CryptoJS.enc.Base64.parse(data.aesKey);
    }

    document.addEventListener('DOMContentLoaded', getAesKey);

    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const data = JSON.stringify({ email, password });

        const iv = CryptoJS.lib.WordArray.random(16);
        const encrypted = CryptoJS.AES.encrypt(data, aesKey, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        });

        const response = await fetch('/login-cifrado', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payload: encrypted.toString(),
                iv: CryptoJS.enc.Base64.stringify(iv)
            })
        });

        const result = await response.json();
        if (response.ok) {
            window.location.href = result.redirect || '/dashboard';
        } else {
            alert(result.message || 'Error de login');
        }
    });

    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('img');
        if (input.type === 'password') {
            input.type = 'text';
            icon.src = "https://w7.pngwing.com/pngs/299/803/png-transparent-close-cross-eye-hidden-vision-commonmat-icon-thumbnail.png";
        } else {
            input.type = 'password';
            icon.src = "https://svgsilh.com/svg_v2/1915455.svg";
        }
    }
</script>
@endsection
