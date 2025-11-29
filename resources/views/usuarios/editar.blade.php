@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Editar Usuario</h3>
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

                        {!! Form::model($usuario, ['method' => 'PATCH', 'route' => ['usuarios.update', $usuario->id]]) !!}

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" >Nombre</label>
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 form-group">
                                <div class="form-group position-relative">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                                            <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 form-group">
                                <div class="form-group position-relative">
                                    <label for="confirm-password">Confirmar Password</label>
                                    <div class="input-group">
                                        {!! Form::password('confirm-password', ['class' => 'form-control', 'id' => 'confirm-password']) !!}
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm-password', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>   
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="">Roles</label>
                                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" 
                                        value="{{ old('fecha_inicio', $usuario->fecha_inicio ? $usuario->fecha_inicio->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control" 
                                        value="{{ old('fecha_fin', $usuario->fecha_fin ? $usuario->fecha_fin->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="form-group p-3">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" class="form-control">
                                    <option value="activo" {{ $usuario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ $usuario->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <button type="submit" class="btn" style="background: #c6d219; border-radius: 20px; color: #fff;">Guardar</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

@endsection
