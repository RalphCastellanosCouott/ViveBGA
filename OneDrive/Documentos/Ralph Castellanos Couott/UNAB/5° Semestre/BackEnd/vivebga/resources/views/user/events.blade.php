@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h3 class="fw-bold mb-4">🎟️ Mis Eventos</h3>

            <!-- EVENTOS PRÓXIMOS -->
            <h5 class="text-success mb-3">Eventos Próximos</h5>
            @if($eventos_proximos->isEmpty())
                <p class="text-muted">No tienes eventos próximos registrados.</p>
            @else
                <div class="list-group mb-4">
                    @foreach ($eventos_proximos as $registro)
                        <a href="{{ route('events.detail', $registro->evento->id) }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $registro->evento->nombre }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($registro->evento->fecha)->format('d M Y') }}</small>
                                </div>
                                <span class="badge bg-success">Próximo</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- EVENTOS PASADOS -->
            <h5 class="text-secondary mb-3">Eventos Asistidos</h5>
            @if($eventos_pasados->isEmpty())
                <p class="text-muted">Aún no has asistido a ningún evento.</p>
            @else
                <div class="list-group">
                    @foreach ($eventos_pasados as $registro)
                        <a href="{{ route('events.detail', $registro->evento->id) }}" class="list-group-item list-group-item-action border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $registro->evento->nombre }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($registro->evento->fecha)->format('d M Y') }}</small>
                                </div>
                                <span class="badge bg-secondary">Asistido</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
