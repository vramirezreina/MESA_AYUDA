<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mesa de Ayuda</title>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Estilos -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            /*background-image: linear-gradient(to bottom, #007080, #c6d219);*/
            background: #c6d219;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-logo {
            display: flex;
            align-items: center;
        }

        .navbar-logo img {
            height: 50px;
            margin-right: 10px;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-ticket {
            background-color: #C7D212;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .hero-section {
           
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        .welcome-card {
            background: white;
            padding: 40px;
           /* border-radius: 20px;*/
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 500px;
            height: 300px;
            font-weight: bold;
            font-size: 26px;
            color: #1d2939;
        }

        .welcome-img {
            background: white;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 700px;
            font-weight: bold;
            font-size: 26px;
            color: #1d2939;
        }
    
        .btn {
            background: #007080;
            color: white;
            width: 200px;
            height: 45px;
            border-radius: 40px;
            font-size: 16px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: 10px;
        }

        .custom-footer {
            background-color: #007080; 
            padding: 20px 0; 
            color: white;
            justify-content: center; 
            align-items: center; 
            text-align: center; 
        }

        .footer-content {
            display: flex;
            justify-content: space-between; 
            align-items: center;
            flex-wrap: wrap; 
            max-width: 1200px; 
            margin: 0 auto; 
            justify-content: center; 
            align-items: center; 
            text-align: center; 
        }

        .footer-left {
            display: flex;
            justify-content: space-between; 
            align-items: center;
            width: 100%; 
            max-width: 900px; 
            
        }

        .footer-item {
            margin-right: 30px; 
        }

        .footer-right {
            background-color: #007080; 
            color: white;
            bottom: 0;
            justify-content: center; 
            align-items: center; 
            text-align: center; 
        }

        .footer-item img {
            max-height: 50px; 
        }

        .footer-text {
            font-size: 14px; 
            color: white; 
        }


        @media (max-width: 768px) {
            .footer-left {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .login-icon {
            margin-left: 10px; 
            color: white;
            text-decoration: none;
        }

        .login-icon i {
            font-size: 40px;
        }
    </style>
</head>
<body class="antialiased"> 

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-logo">
            <img src="{{ asset('img/logo1.png') }}" alt="InfiHuila Logo">
        </div>

        <div class="navbar-menu">
        
            @if (Route::has('login'))
                @auth
                @else
                <a href="{{ route('login') }}" class="login-icon">
                    <button class="btn">Iniciar Sesion  
                        <i class="fas fa-user-circle" style="font-size:30px; color: white; margin-left: 10px; "></i>
                    </button>
                </a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero-section">
        <div class="welcome-card">
            <h1>Bienvenido al<br>Soporte Infihuila</h1>
            <h2 style="font-size: 22px; font-weight: normal;">¿Cómo podemos ayudarte?</h2>
        </div>
        
        @php
            $setting = \App\Models\Setting::latest()->first();
        @endphp


        <div class="welcome-img">
            <img src="{{ $setting && $setting->banner_image ? asset('storage/' . $setting->banner_image) : asset('img/default-banner.jpg') }}" alt="Banner" style="max-width:100%;">
        </div>


    </div>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-item">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo">
                </div>
                <div class="footer-item">
                    <span>Soporte: sistema.tic@infihuila.gov.co</span>
                </div>
                <div class="footer-item">
                    <span>Oficina Gestión TI</span>
                </div>
            </div>
            
        </div>
        
    </footer>
    <div class="footer-right">
        <span>© 2025 InfiHuila. Todos los derechos reservados.</span>
    </div>


</body>
</html>
