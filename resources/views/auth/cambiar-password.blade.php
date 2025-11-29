@extends('layouts.app')

@section('content')
<!-- Modal -->
<div class="modal fade" id="cambiarClaveModal" tabindex="-1" aria-labelledby="cambiarClaveLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('password.actualizar') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="cambiarClaveLabel">Cambiar contraseña</h5>
        </div>
        <div class="modal-body">
    
        <div class="form-group position-relative">
            <label>Nueva contraseña</label>
            <input type="password" id="passwordInput" name="password" class="form-control" required>
            <span toggle="#passwordInput" class="fa fa-fw fa-eye field-icon toggle-password"
              style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></span>
          </div>

          <div class="form-group position-relative mt-3">
            <label>Confirmar contraseña</label>
            <input type="password" id="passwordConfirmInput" name="password_confirmation" class="form-control" required>
            <span toggle="#passwordConfirmInput" class="fa fa-fw fa-eye field-icon toggle-password"
              style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></span>
          </div>

        <div class="mt-3 text-muted" style="font-size: 0.9rem;">
          <ul class="mt-3 text-muted" style="font-size: 0.9rem; padding-left: 1.2rem;">
            <li>Debe tener al menos 8 caracteres.</li>
            <li>Debe incluir una letra mayúscula.</li>
            <li>Debe incluir una letra minúscula.</li>
            <li>Debe incluir un número.</li>
            <li>Debe incluir un carácter especial (por ejemplo: @, #, $, %, &).</li>
          </ul
        </div>
          
        </div>

      
        <div class="modal-footer">
          <button type="submit" class="btn" style="background: #c6d219; border-radius: 20px; color: #fff;">Actualizar contraseña</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- FontAwesome si aún no lo tienes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Mostrar el modal automáticamente
    var modal = new bootstrap.Modal(document.getElementById('cambiarClaveModal'));
    modal.show();

    // Toggle de visibilidad de contraseña
    document.querySelectorAll('.toggle-password').forEach(function (element) {
      element.addEventListener('click', function () {
        const input = document.querySelector(this.getAttribute('toggle'));
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    });
  });
</script>
@endsection
