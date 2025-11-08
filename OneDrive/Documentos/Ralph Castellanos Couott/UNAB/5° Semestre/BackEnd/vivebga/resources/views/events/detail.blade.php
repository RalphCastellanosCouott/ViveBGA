@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto; padding: 30px;">

    {{-- Botón volver --}}
    <a href="{{ url()->previous() }}" 
       style="display: inline-block; margin-bottom: 20px; text-decoration: none; border: 1px solid #444; padding: 6px 10px; border-radius: 5px;">
        ← Volver
    </a>

    {{-- Imagen principal del evento --}}
    @if($evento->imagen)
        <img src="{{ asset('storage/' . $evento->imagen) }}" 
             alt="{{ $evento->nombre }}" 
             style="width: 100%; height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 20px;">
    @else
        <div style="width: 100%; height: 400px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin-bottom: 20px;">
            <span style="color: #777;">Sin imagen disponible</span>
        </div>
    @endif

    {{-- Información general --}}
    <h1 style="margin-bottom: 10px;">{{ $evento->nombre }}</h1>
    <p style="margin-bottom: 20px;">{{ $evento->descripcion }}</p>

    <div style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px;">
        <p><strong>Fecha:</strong> {{ $evento->fecha }}</p>
        <p><strong>Hora:</strong> {{ $evento->hora }}</p>
        <p><strong>Ubicación:</strong> {{ $evento->direccion }}</p>
        <p><strong>Precio:</strong> ${{ number_format($evento->precio, 0, ',', '.') }}</p>
    </div>

    {{-- Información del promotor (si existe relación con usuarios) --}}
    @if(isset($evento->user))
        <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
            <h3>Promotor del evento</h3>
            <p><strong>Nombre:</strong> {{ $evento->user->name }} {{ $evento->user->apellido ?? '' }}</p>
            <p><strong>Descripción:</strong> {{ $evento->user->descripcion ?? 'Sin descripción disponible' }}</p>

            @if($evento->user->foto_perfil)
                <img src="{{ asset('storage/' . $evento->user->foto_perfil) }}" 
                     alt="Foto del promotor" 
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; margin-top: 10px;">
            @endif
        </div>
    @endif

    {{-- Botón para registrarse o comprar entrada (opcional futuro) --}}
    <div style="margin-top: 30px;">
        <button style="padding: 10px 20px; border: 1px solid #333; border-radius: 5px; background: none; cursor: pointer;">
            Registrarme al evento
        </button>
    </div>

</div>
@endsection
