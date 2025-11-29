@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Crear Ticket</h3>
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

                        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value="">Seleccione una opción</option>
                                    <option value="hardware" title="Problemas físicos con computadoras, impresoras." {{ old('tipo') == 'hardware' ? 'selected' : '' }}>
                                        Hardware
                                    </option>
                                    <option value="software" title="Problemas con programas, sistemas operativos o aplicaciones." {{ old('tipo') == 'software' ? 'selected' : '' }}>
                                        Software
                                    </option>
                                    <option value="red" title="Problemas de conexión a internet o a la red interna." {{ old('tipo') == 'red' ? 'selected' : '' }}>
                                        Red
                                    </option>
                                </select>
                             </div>


                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required>{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="archivo">Archivo (opcional):</label>
                                <div class="custom-file">
                                    <input type="file" name="archivo" id="archivo" class="custom-file-input">
                                    <label class="custom-file-label" for="archivo">Seleccionar archivo</label>
                                </div>
                            </div>


                            <div class="form-group mt-4">
                                <button type="submit" class="btn" style="background: rgb(198, 210, 25)">
                                    <i class="fa-solid fa-paper-plane"></i> Enviar
                                </button>
                                <a href="{{ route('tickets.index') }}" class="btn text-white" style="background: rgb(238, 173, 26)">
                                    <i class="fa-solid fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar el nombre del archivo seleccionado
        const archivoInput = document.querySelector('.custom-file-input');
        archivoInput.addEventListener('change', function (e) {
            let fileName = e.target.files[0]?.name || 'Seleccionar archivo';
            let nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        // Evitar envíos múltiples
        document.getElementById("formTicket").addEventListener("submit", function (e) {
            let btnGuardar = document.getElementById("btnGuardar");
            btnGuardar.disabled = true; // Desactivar botón
            btnGuardar.innerText = "Guardando...";
        });

    });
</script>
@endpush

