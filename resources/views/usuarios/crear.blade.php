@extends('layouts.app')
@stack('scripts')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Alta de Usuarios</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">    

                        @if ($errors->any())                                                
                            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Revise los campos!</strong>                        
                                @foreach ($errors->all() as $error)                                    
                                    <span class="badge badge-danger">{{ $error }}</span>
                                @endforeach                        
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {!! Form::open(['route' => 'usuarios.store','method'=>'POST']) !!}
                        <div class="row">

                            <div class="col-md-12 form-group">
                                <label for="name">Nombre</label>
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="email">E-mail</label>
                                {!! Form::text('email', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="password">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="confirm-password">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm-password', this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="">Roles</label>
                                {!! Form::select('roles[]', $roles, [], ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="fecha_inicio">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}">
                            </div>

                            <div class="col-md-12 form-group">
                                <label for="fecha_fin">Fecha de fin</label>
                                <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
                            </div>

                            <div class="col-md-12 ">
                                <button type="submit" class="btn" style="background: #c6d219; border-radius: 15px; color: #fff;">Guardar</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
