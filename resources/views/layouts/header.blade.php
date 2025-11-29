<form class="form-inline mr-auto" action="#" style="background: #007080">
    <ul class="navbar-nav mr-3">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
</form>


<ul class="navbar-nav text-white" style="background: #007080">
    @if(\Illuminate\Support\Facades\Auth::user())
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center text-white">
                    <div class="text-center rounded-circle mr-2"
                    style="width: 50px; height: 50px; font-size:25px; display: flex; align-items: center; justify-content: center; font-weight: 800; background-color: #c6d219; color: white;">
                    {{ strtoupper(substr(Auth::user()->name, 0,1)) }}

                    </div>
                <div class="d-sm-none d-lg-inline-block font-weight-bold">
                    ¡Hola! {{ Auth::user()->first_name }}
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    Bienvenido, {{ Auth::user()->name }}
                </div>

                <a class="dropdown-item has-icon edit-profile" data-id="{{ Auth::id() }}">
                    <i class="fa fa-user"></i> Editar Perfil de Usuario
                </a>

<!--NUEVO CONTRSEÑA-->
                <a href="#" class="dropdown-item has-icon" data-toggle="modal" data-target="#changePasswordModal">
                    <i class="fas fa-key"></i> Cambiar contraseña
                </a>
                
                <a href="{{ url('logout') }}" class="dropdown-item has-icon text-danger"
                   onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesion
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    @else
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
               class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block text-white">
                    {{ __('messages.common.hello') }}
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    {{ __('messages.common.login') }} / {{ __('messages.common.register') }}
                </div>
                <a href="{{ route('login') }}" class="dropdown-item has-icon">
                    <i class="fas fa-sign-in-alt"></i> {{ __('messages.common.login') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('register') }}" class="dropdown-item has-icon">
                    <i class="fas fa-user-plus"></i> {{ __('messages.common.register') }}
                </a>
            </div>
        </li>
    @endif
</ul>




<script>
document.addEventListener('DOMContentLoaded', function () {
    let form = document.getElementById('changePasswordForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            let newPass = document.querySelector('input[name="new_password"]').value;
            let confirmPass = document.querySelector('input[name="new_password_confirmation"]').value;

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Las nuevas contraseñas no coinciden.');
            }
        });
    }
});

</script>


