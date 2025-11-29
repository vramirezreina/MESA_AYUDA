@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Auditoría del Ticket #{{ $ticket->id }}</h3>
    </div>
    <div class="section-body">

        <div class="card">
            <div class="card-header bg-dark text-white">
                <strong>Detalles de Auditoría Técnica</strong>
            </div>
            <div class="card-body">

                @if($ticket->auditorias && $ticket->auditorias->count() > 0)
                    <ul class="list-group">
                        @foreach($ticket->auditorias as $auditoria)
                            <li class="list-group-item">
                                <strong>Acción:</strong> {{ ucfirst($auditoria->evento) }}<br>
                                <strong>Usuario:</strong> {{ $auditoria->usuario->name ?? 'Desconocido' }}<br>
                                <strong>Detalle:</strong> {{ $auditoria->detalle }}<br>
                                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($auditoria->fecha_hora)->format('d/m/Y H:i') }}
                                
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay auditoría registrada para este ticket.</p>
                @endif

                <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-3">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>

            </div>
        </div>
    </div>
</section>
@endsection
