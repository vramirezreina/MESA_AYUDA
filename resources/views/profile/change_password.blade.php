
<div id="changePasswordModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
       
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" aria-label="Close" class="close outline-none" data-dismiss="modal"></button>
            </div>
            <form method="POST" id="changePasswordForm" action="{{ route('user.change.password') }}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label>Contraseña Actual:</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nueva Contraseña:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Confirmar Contraseña:</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
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

        <button type="submit" class="btn" style="background: #c6d214; color:white">Cambiar</button>
    </div>

</form>

        </div>
    </div>
</div>
<?php
