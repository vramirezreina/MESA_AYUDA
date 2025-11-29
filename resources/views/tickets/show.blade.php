@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detalle del Ticket #{{ $ticket->id }}</h3>
    </div>
    <div class="section-body">
        <div class="row">
          
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-2">
                            <strong>Creado por:</strong> {{ $ticket->creador->name ?? 'N/A' }}
                        </div>

                        <div class="mb-2">
                            <strong>Tipo:</strong> {{ ucfirst($ticket->tipo) }}
                        </div>

                        <div class="mb-2">
                            <strong>Descripción:</strong> {{ $ticket->descripcion }}
                        </div>

                        <div class="mb-2">
                            <strong>Técnico:</strong> {{ $ticket->asignado->name ?? 'No asignado' }}
                        </div>

                        <div class="mb-2">
                            <strong>Estado:</strong>
                            @php
                                $estadoColor = [
                                    'abierto' => 'badge bg-primary text-white',
                                    'pendiente' => 'badge bg-warning text-dark',
                                    'resuelto' => 'badge bg-success text-dark',
                                    'cerrado' => 'badge bg-secondary text-dark'
                                ];
                            @endphp
                            <span class="{{ $estadoColor[$ticket->estado] ?? 'badge bg-dark' }}">
                                {{ ucfirst($ticket->estado) }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <strong>Fecha de creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </div>

                        @if ($ticket->estado === 'resuelto')
                            <div class="mb-2">
                                <strong>Tiempo para resolver:</strong>
                                {{ $ticket->created_at->diffForHumans($ticket->updated_at, true) }}
                            </div>
                        @endif
                        <div class="mb-2">
                            <strong>Comentario Soporte:</strong>  {{ $ticket->comentario_soporte ?? 'Sin comentario' }}
                        </div>

                        <div class="mb-3">
                            <strong>Archivo:</strong>
                            @if ($ticket->archivo)
                                <a href="#" data-toggle="modal" data-target="#archivoModal">
                                    <i class="fa-solid fa-paperclip fa-lg text-info"></i> Ver archivo
                                </a>
                            @else
                                <span class="text-muted">No adjunto</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('tickets.index') }}" class="btn mt-4" style="background: rgb(238, 173, 26)">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header  text-white" style="background: #c6d219">
                        <strong>Historial del Ticket</strong>
                    </div>
                    <div class="card-body">
                        @forelse ($ticket->historial as $item)
                            <div class="mb-2">
                                <strong>{{ ucfirst($item->evento) }}:</strong>
                                <br>
                                <small>{{ $item->detalle }}</small>
                                <br>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($item->fecha_hora)->format('d/m/Y H:i') }}</span>
                            </div>
                            <hr>
                        @empty
                            <p class="text-muted">No hay historial aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($ticket->archivo)
<div class="modal fade" id="archivoModal" tabindex="-1" role="dialog" aria-labelledby="archivoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Archivo adjunto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                @php
                    $extension = pathinfo($ticket->archivo, PATHINFO_EXTENSION);
                @endphp

                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ asset('storage/' . $ticket->archivo) }}" class="img-fluid rounded" alt="Archivo adjunto">
                @else
                    <iframe src="{{ asset('storage/' . $ticket->archivo) }}" width="100%" height="500px" frameborder="0"></iframe>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
