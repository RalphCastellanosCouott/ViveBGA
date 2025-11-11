@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Datos del organizador --}}
        <div style="display: flex; align-items: center; margin-bottom: 30px;">
            @if ($organizador->foto_perfil)
                <img src="{{ asset($organizador->foto_perfil) }}" alt="Foto de perfil"
                    style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-right:20px;">
            @else
                <div style="width:120px; height:120px; border-radius:50%; background:#ddd; margin-right:20px;"></div>
            @endif
            <div>
                <h2>{{ $organizador->name }} {{ $organizador->apellido ?? '' }}</h2>
                <p>{{ $organizador->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div style="color: green; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        {{-- Eventos próximos --}}
        <h3>Eventos próximos</h3>
        @if ($eventosProximos->isEmpty())
            <p>No tiene eventos próximos.</p>
        @else
            <div
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
                @foreach ($eventosProximos as $evento)
                    <div style="border:1px solid #ccc; border-radius:8px; padding:15px;">
                        <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->nombre }}"
                            style="width:100%; border-radius:8px;">
                        <h4>{{ $evento->nombre }}</h4>
                        <p>{{ $evento->fecha }} - {{ $evento->hora }}</p>
                        <p><strong>Precio:</strong>
                            @if ($evento->precio && $evento->precio > 0)
                                ${{ number_format($evento->precio, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>
                        <a href="{{ route('events.detail', $evento->id) }}">Ver detalles</a>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Eventos pasados --}}
        <h3>Eventos realizados</h3>
        @if ($eventosPasados->isEmpty())
            <p>No tiene eventos realizados aún.</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                @foreach ($eventosPasados as $evento)
                    <div style="border:1px solid #ccc; border-radius:8px; padding:15px;">
                        <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->nombre }}"
                            style="width:100%; border-radius:8px;">
                        <h4>{{ $evento->nombre }}</h4>
                        <p>{{ $evento->fecha }} - {{ $evento->hora }}</p>
                        <p><strong>Precio:</strong>
                            @if ($evento->precio && $evento->precio > 0)
                                ${{ number_format($evento->precio, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>
                        <a href="{{ route('events.detail', $evento->id) }}">Ver detalles</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
