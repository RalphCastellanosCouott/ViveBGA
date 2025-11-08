@extends('layouts.app')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 20px;">Eventos próximos</h1>

    @if($eventos->isEmpty())
        <p>No hay eventos próximos en este momento.</p>
    @else
        <div class="eventos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @foreach($eventos as $evento)
                <div class="evento-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #fff;">
                    @if($evento->imagen)
                        <img src="{{ asset('storage/' . $evento->imagen) }}" 
                             alt="{{ $evento->nombre }}" 
                             style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
                    @else
                        <div style="width: 100%; height: 180px; background: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span>Sin imagen</span>
                        </div>
                    @endif

                    <h2 style="margin-top: 10px;">{{ $evento->nombre }}</h2>
                    <p>{{ $evento->descripcion }}</p>
                    <p><strong>Fecha:</strong> {{ $evento->fecha }} - <strong>Hora:</strong> {{ $evento->hora }}</p>
                    <p><strong>Precio:</strong> ${{ number_format($evento->precio, 0, ',', '.') }}</p>

                    <a href="{{ route('evento.detalle', ['id' => $evento->id]) }}" 
                       style="display: inline-block; margin-top: 10px; text-decoration: none; color: #007bff;">
                        Ver evento
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
