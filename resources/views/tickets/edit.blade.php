@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Editar Ticket</h3>
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

                        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <input type="text" name="tipo" id="tipo" value="{{ old('tipo', $ticket->tipo) }}" class="form-control"
                                    {{ auth()->id() != $ticket->usuario_creador_id ? 'disabled' : '' }}
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                @if(auth()->id() == $ticket->usuario_creador_id)
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required>{{ old('descripcion', $ticket->descripcion) }}</textarea>
                                @else
                                    <input type="hidden" name="descripcion" value="{{ $ticket->descripcion }}">
                                    <p class="form-control-plaintext">{{ $ticket->descripcion }}</p>
                                @endif
                            </div>


                            <div class="form-group">
                                <label for="estado">Estado:</label>
                                <select name="estado" id="estado" class="form-control">
                                <option value="abierto" {{ old('estado', $ticket->estado) == 'abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="pendiente" {{ old('estado', $ticket->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="resuelto" {{ old('estado', $ticket->estado) == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                <option value="cerrado" {{ old('estado', $ticket->estado) == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                </select>
                            </div>

                            @hasanyrole('Soporte|Administrador|Super_Administrador')
                                <div class="form-group">
                                    <label for="comentario_soporte">Comentario Soporte:</label>
                                    <textarea name="comentario_soporte" id="comentario_soporte" class="form-control" rows="3">{{ old('comentario_soporte', $ticket->comentario_soporte) }}</textarea>
                                </div>
                            @endhasanyrole



                            <div class="form-group mt-4">
                                <button type="submit" class="btn text-white" style="background: rgb(238, 173, 26)">
                                    <i class="fa-solid fa-pen-to-square"></i> Actualizar
                                </button>
                                <a href="{{ route('tickets.index') }}" class="btn " style="background: rgb(198, 210, 25)">
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
