@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Tickets</h3>
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

                        <div class="d-flex justify-content-between align-items-center m-3">
                       
                            @role('Usuario')
                            <a class="btn" style="background: #c6d219; border-radius: 20px; color: #fff;" href="{{ route('tickets.create') }}">
                                <i class="fa-solid fa-plus" style="color: #ffffff;"></i> Nuevo Ticket
                            </a>
                            @endrole

                            @role('Administrador|Super_Administrador')
    
                            <a class="btn mb-2" style="background: #c6d219; border-radius: 20px; color: #fff;"
                                href="{{ route('exportar.tickets', request()->query()) }}">
                                <i class="fa-solid fa-file-export"></i> Exportar
                            </a>
                            @endrole

                        </div>

                        @role('Administrador|Super_Administrador')
                        {{-- Filtros --}}
                        <div class="collapse mx-3 mb-3 show" id="filtroTickets">
                            <form method="GET" action="{{ route('tickets.index') }}" class="form-inline flex-wrap gap-2">
                                {{-- Filtro por Usuario Creador --}}
                                <div class="form-group mr-2">
                                    <label for="creador" class="mr-2">Creador:</label>
                                    <select name="creador" id="creador" class="form-control">
                                        <option value="">-- Todos --</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ request('creador') == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filtro por Estado --}}
                                <div class="form-group mr-2">
                                    <label for="estado" class="mr-2">Estado:</label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="abierto" {{ request('estado') == 'abierto' ? 'selected' : '' }}>Abierto</option>
                                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="resuelto" {{ request('estado') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                        <option value="cerrado" {{ request('estado') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                    </select>
                                </div>


                                {{-- Filtro por Tipo --}}
                                <div class="form-group mr-2">
                                    <label for="tipo" class="mr-2">Tipo:</label>
                                    <select name="tipo" id="tipo" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="Hardware" {{ request('tipo') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                        <option value="Software" {{ request('tipo') == 'Software' ? 'selected' : '' }}>Software</option>
                                        <option value="Red" {{ request('tipo') == 'Red' ? 'selected' : '' }}>Red</option>
                                    </select>
                                </div>

                                {{-- Filtro por Rango de Tiempo --}}
                                <div class="form-group mr-2">
                                    <label for="rango" class="mr-2">Rango:</label>
                                    <select name="rango" id="rango" class="form-control">
                                        <option value="">-- Todos --</option>
                                        <option value="mensual" {{ request('rango') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                        <option value="bimensual" {{ request('rango') == 'bimensual' ? 'selected' : '' }}>Bimensual</option>
                                        <option value="trimestral" {{ request('rango') == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                                        <option value="semestral" {{ request('rango') == 'semestral' ? 'selected' : '' }}>Semestral</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn ml-2 text-white" style="background: rgb(238, 173, 26)">Aplicar</button>
                            </form>
                            @endrole
                        </div>



                        <table class="table table-striped mt-2">
                            <thead style="background-color:#007080">
                                <tr>
                                    <th style="color:#fff;">ID</th>
                                    <th style="color:#fff;">Tipo</th>
                                    <th style="color:#fff;">Descripción</th>
                                    <th style="color:#fff;">Archivo</th>
                                    <th style="color:#fff;">Creado por</th>
                                    <th style="color:#fff;">Usuario Asignado</th>
                                    <th style="color:#fff;">Estado</th>
                                    <th style="color:#fff;">Fecha de Creación</th>
                                    <th style="color:#fff;">Comentario Soporte</th>
                                    <th style="color:#fff;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr onclick="window.location='{{ route('tickets.show', $ticket->id) }}'" style="cursor: pointer;">
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ ucfirst($ticket->tipo) }}</td>
                                        <td>{{ $ticket->descripcion }}</td>
                                        <td onclick="event.stopPropagation();">
                                            @if ($ticket->archivo)
                                                <a href="{{ asset('storage/' . $ticket->archivo) }}" target="_blank" class="btn btn-sm text-white" style="background: rgb(238, 173, 26)">
                                                    <i class="fa-solid fa-paperclip"></i> Archivo
                                                </a>
                                            @else
                                                <span class="text-muted">No adjunto</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->creador->name ?? 'N/A' }}</td>  

                                        <td onclick="event.stopPropagation();">
                                            @hasanyrole('Administrador|Super_Administrador')
                                                <form action="{{ route('tickets.asignar', $ticket->id) }}" method="POST">
                                                    @csrf
                                                    <select name="usuario_asignado_id" class="form-control form-control-sm  text-dark" onchange="this.form.submit()">
                                                        <option value="">Asignar</option>
                                                        @foreach ($soportes as $soporte)
                                                            <option value="{{ $soporte->id }}" {{ $ticket->usuario_asignado_id == $soporte->id ? 'selected' : '' }}>
                                                                {{ $soporte->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                @if($ticket->asignado)
                                                    <span class="badge text-dark" style="background: rgb(198, 210, 25)">
                                                        {{ $ticket->asignado->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary text-white">No asignado</span>
                                                @endif
                                            @endhasanyrole
                                        </td>

                                        <td>
                                            @php
                                                $estadoColor = [
                                                    'abierto' => 'badge bg-primary text-white',
                                                    'pendiente' => 'badge bg-warning text-white',
                                                    'resuelto' => 'badge bg-success text-white',
                                                    'cerrado' => 'badge bg-secondary text-dark'
                                                ];
                                            @endphp
                                            <span class="{{ $estadoColor[$ticket->estado] ?? 'badge bg-dark' }}">
                                                {{ ucfirst($ticket->estado) }}
                                            </span>
                                        </td>

                                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        
                                        <td onclick="event.stopPropagation();">
                                            @if($ticket->comentario_soporte)
                                                <span title="{{ $ticket->comentario_soporte }}">
                                                    <span class="d-inline-block px-2 py-1 bg-light border text-dark" style="max-width: 200px;">
                                                        <i class="fa-solid  text-info mr-1"></i>
                                                        {{ \Illuminate\Support\Str::limit($ticket->comentario_soporte, 25) }}
                                                    </span>
                                                </span>
                                            @else
                                                <span class="text-muted">Sin comentario</span>
                                            @endif
                                        </td>

                                        <td onclick="event.stopPropagation();">
                                        @hasanyrole('Administrador|Soporte|Super_Administrador')
                                            <a class="btn btn-sm" href="{{ route('tickets.edit', $ticket->id) }}" style="background-color: #EEAD1A; color: #fff;">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endhasanyrole



                                            <a class="btn btn-sm btn-info" href="{{ route('tickets.show', $ticket->id) }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination justify-content-end m-3" style="backroung: #c6d219">
                            {!! $tickets->links() !!}
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
