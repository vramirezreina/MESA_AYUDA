@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Historial de Auditoría</h3>
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
                            <a class="btn" style="background: #c6d219; border-radius: 20px; color: #fff;" href="{{ route('export.auditorias') }}">
                                <i class="fa-solid fa-file-export"></i> Exportar
                            </a>
                        </div>

                        <table class="table table-striped mt-2">
                            <thead style="background-color:#007080">
                                <tr>
                                    <th style="color:#fff;">ID</th>
                                    <th style="color:#fff;">Usuario</th>
                                    <th style="color:#fff;">Acción</th>
                                    <th style="color:#fff;">Usuario Afectado</th>
                                    <th style="color:#fff;">Cambios</th>
                                    <th style="color:#fff;">Fecha de Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user?->name ?? 'Desconocido' }}</td>
                                    <td>
                                        <span class="badge text-white" style="background-color: rgb(238, 173, 26)">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $model = app($log->model_type)::find($log->model_id);
                                        @endphp
                                        {{ $model?->name ?? 'Desconocido' }}
                                    </td>
                                    <td>
                                        <ul class="list-unstyled text-start">
                                        @php
                                            $changes = json_decode($log->changes, true);
                                            if (is_array($changes)) {
                                                unset($changes['updated_at']);
                                            } else {
                                                $changes = [];
                                            }
                                        @endphp

                                            @foreach($changes as $field => $value)
                                                <li>
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                                    @if (is_array($value))
                                                        @php
                                                            $oldValue = $value[0] ?? 'N/A';
                                                            $newValue = $value[1] ?? 'N/A';

                                                            // Formateo si es fecha
                                                            if (in_array($field, ['fecha_inicio', 'fecha_fin'])) {
                                                                $oldValue = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d/m/Y H:i') : 'N/A';
                                                                $newValue = $newValue ? \Carbon\Carbon::parse($newValue)->format('d/m/Y H:i') : 'N/A';
                                                            }

                                                            // Estado con badge
                                                            if ($field == 'estado') {
                                                                $oldValue = $oldValue == 'activo'
                                                                    ? '<span class="badge bg-success">Activo</span>'
                                                                    : '<span class="badge bg-danger">Inactivo</span>';
                                                                $newValue = $newValue == 'activo'
                                                                    ? '<span class="badge bg-success">Activo</span>'
                                                                    : '<span class="badge bg-danger">Inactivo</span>';
                                                            }

                                                            
                                                            if ($field == 'rol') {
                                                                $oldValue = '<span class="badge  text-dark" style="background: rgb(198, 210, 25)">' . $oldValue . '</span>';
                                                                $newValue = '<span class="badge text-dark" style="background: rgb(198, 210, 25)">' . $newValue . '</span>';
                                                            }
                                                        @endphp
                                                        {!! $oldValue !!} ➝ {!! $newValue !!}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="pagination justify-content-end m-3">
                            {{ $logs->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#success-alert').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush
