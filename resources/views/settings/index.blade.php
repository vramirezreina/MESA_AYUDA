@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Configuración del Banner</h3>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="d-flex justify-content-end align-items-center m-3">
                        <button class="btn mb-2" style="background: #c6d219; border-radius: 20px; color: #fff;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fa-solid fa-upload"></i> Subir Nueva Imagen
                        </button>
                    </div>

                    <div class="table-responsive p-3">
                        <table class="table table-bordered ">
                            <thead class="table " style="background:#007080;">
                                <tr>
                                    <th class="text-white">ID</th>
                                    <th class="text-white">Imagen</th>
                                    <th class="text-white">Fecha de Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>{{ $setting->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $setting->banner_image) }}" alt="Banner" style="max-height: 100px;">
                                        </td>
                                        <td>{{ $setting->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No hay imágenes registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para subir nueva imagen -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background: #007080;">
                    <h5 class="modal-title text-white" id="uploadModalLabel">Subir Nueva Imagen</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="banner_image" class="form-label">Selecciona una imagen</label>
                        <input type="file" name="banner_image" id="banner_image" class="form-control" required accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" style="background: #c6d219; color: #fff;">Subir Imagen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
