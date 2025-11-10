@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        {{-- Formulario de búsqueda --}}
    <form action="{{ route('event.search') }}" method="GET" class="mb-4 d-flex justify-content-center">
        <input type="text" name="query" class="form-control me-2" placeholder="Buscar eventos u organizadores..." value="{{ request('query') }}" style="max-width: 400px;">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

        <h1 style="text-align: center; margin-bottom: 30px;">Eventos disponibles</h1>

        {{-- Si no hay eventos --}}
        @if ($eventos->isEmpty())
            <p style="text-align: center;">No hay eventos próximos en este momento.</p>
        @else
            {{-- Contenedor en formato grid responsivo --}}
            <div class="eventos-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">

                {{-- Recorremos los eventos --}}
                @foreach ($eventos as $evento)
                    <div class="evento-card"
                        style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">

                        {{-- Imagen del evento --}}
                        @if ($evento->imagen)
                            <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->nombre }}"
                                style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                        @else
                            <div
                                style="width: 100%; height: 180px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 10px;">
                                <span style="color: #777;">Sin imagen</span>
                            </div>
                        @endif

                        {{-- Información del evento --}}
                        <h2 style="font-size: 1.3em; margin-bottom: 10px;">{{ $evento->nombre }}</h2>
                        <p
                            style="display: -webkit-box; 
                            -webkit-line-clamp: 2; 
                            -webkit-box-orient: vertical; 
                            overflow: hidden;">
                            {{ $evento->descripcion }}
                        </p>
                        <p style="margin-bottom: 5px;"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
                        <p style="margin-bottom: 5px;"><strong>Hora:</strong> {{ $evento->hora }}</p>
                        <p><strong>Precio:</strong>
                            @if ($evento->precio && $evento->precio > 0)
                                ${{ number_format($evento->precio, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>

                        {{-- Botón para ver detalles del evento --}}
                        <a href="{{ route('events.detail', ['id' => $evento->id]) }}"
                            style="display: inline-block; padding: 8px 12px; border: 1px solid #333; border-radius: 5px; text-decoration: none;">
                            Ver evento
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
