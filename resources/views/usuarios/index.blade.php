@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Usuarios</h3>
    </div>

    @if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive">

                        <div class="d-flex justify-content-between align-items-center m-3 flex-wrap">
                            {{-- Botón Nuevo (izquierda) --}}
                            <a class="btn mb-2" style="background: #c6d219; border-radius: 20px; color: #fff;" href="{{ route('usuarios.create') }}">
                                <i class="fa-solid fa-user-plus"></i> Nuevo
                            </a>

                            {{-- Contenedor derecho: barra de búsqueda + botones --}}
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                {{-- Barra de búsqueda --}}
                                <form method="GET" action="{{ route('usuarios.index') }}" class="form-inline mb-2 mr-2">
                                    <div class="input-group">
                                        <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre" value="{{ request('busqueda') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                {{-- Botón Exportar --}}
                                <a class="btn mb-2" style="background: #c6d219; border-radius: 20px; color: #fff;"
                                    href="{{ route('exportar.usuarios', ['estado' => request('estado'), 'rol' => request('rol'), 'busqueda' => request('busqueda')]) }}">
                                    <i class="fa-solid fa-file-export"></i> Exportar
                                </a>

                                {{-- Botón Filtrar --}}
                                <button class="btn mb-2" style="background: #007080; border-radius: 20px; color: #fff;" type="button" data-toggle="collapse" data-target="#filtroUsuarios">
                                    <i class="fa-solid fa-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>


                        {{-- Formulario de filtro --}}
                        <div class="collapse mx-3 mb-3" id="filtroUsuarios">
                            <form method="GET" action="{{ route('usuarios.index') }}" class="form-inline">
                                <div class="form-group mr-2">
                                    <label for="rol" class="mr-2">Rol:</label>
                                    <select name="rol" id="rol" class="form-control">
                                        <option value="">-- Todos --</option>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol }}" {{ request('rol') == $rol ? 'selected' : '' }}>{{ $rol }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mr-2">
                                    <label for="estado" class="mr-2">Estado:</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn ml-2 text-white" style="background: rgb(238, 173, 26)">Aplicar</button>
                            </form>
                        </div>

                        {{-- Tabla de usuarios --}}
                        <table class="table table-striped mt-2">
                            <thead style="background-color:#007080">
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th style="color:#fff;">Nombre</th>
                                    <th style="color:#fff;">Correo electrónico</th>
                                    <th style="color:#fff;">Rol</th>
                                    <th style="color:#fff;">Fecha de registro</th>
                                    <th style="color:#fff;">Fecha de Inicio</th>
                                    <th style="color:#fff;">Fecha de Fin</th>
                                    <th style="color:#fff;">Estado</th>
                                    <th style="color:#fff;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                <tr>
                                    <td style="display: none;">{{ $usuario->id }}</td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @foreach($usuario->getRoleNames() as $rolNombre)
                                            <span class="badge bg-secondary text-dark">{{ $rolNombre }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $usuario->fecha_inicio ? date('d/m/Y', strtotime($usuario->fecha_inicio)) : 'N/A' }}</td>
                                    <td>{{ $usuario->fecha_fin ? date('d/m/Y', strtotime($usuario->fecha_fin)) : 'N/A' }}</td>
                                    <td>
                                        @if($usuario->estado === 'activo')
                                            <span class="badge bg-success text-white">Activo</span>
                                        @else
                                            <span class="badge bg-danger text-white">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn" href="{{ route('usuarios.edit', $usuario->id) }}" style="background-color: #EEAD1A; color: #fff;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination justify-content-end">
                            {{ $usuarios->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush

@endsection
