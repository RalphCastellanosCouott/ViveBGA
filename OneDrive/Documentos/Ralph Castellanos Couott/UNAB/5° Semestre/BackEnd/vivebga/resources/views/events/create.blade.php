@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 30px;">
    <h2 style="margin-bottom: 25px;">Crear nuevo evento</h2>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 15px;">

            <label>Nombre del evento:</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required>

            <label>Descripción:</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>

            <label>Categoría:</label>
            <input type="text" name="categoria" value="{{ old('categoria') }}" required>

            <label>Fecha:</label>
            <input type="date" name="fecha" value="{{ old('fecha') }}" required>

            <label>Hora:</label>
            <input type="time" name="hora" value="{{ old('hora') }}" required>

            <label>Dirección:</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}" required>

            <label>Cupos totales:</label>
            <input type="number" name="cupos" value="{{ old('cupos') }}" min="1">

            <label>Precio (dejar vacío si es gratuito):</label>
            <input type="number" name="precio" value="{{ old('precio') }}" step="0.01">

            <label>Imagen del evento:</label>
            <input type="file" name="imagen" accept="image/*">

            <button type="submit" style="padding: 10px 15px; border: none; background: #007bff; color: white; border-radius: 5px; cursor: pointer;">
                Guardar evento
            </button>
        </div>
    </form>
</div>
@endsection