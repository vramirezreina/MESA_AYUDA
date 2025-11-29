@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Configuración del sitio</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="banner_image" class="form-label">Imagen del banner</label><br>
            @if($setting->banner_image)
                <img src="{{ asset('storage/' . $setting->banner_image) }}" alt="Banner actual" style="max-width: 300px;"><br><br>
            @endif
            <input type="file" name="banner_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
