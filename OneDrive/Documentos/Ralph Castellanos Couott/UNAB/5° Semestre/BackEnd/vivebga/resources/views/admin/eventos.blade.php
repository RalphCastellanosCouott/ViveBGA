@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <h2 class="mb-4">Lista de Eventos</h2>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Organizador</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Inscritos</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eventos as $evento)
                        <tr>
                            <td>{{ $evento->id }}</td>
                            <td>{{ $evento->nombre }}</td>
                            <td>{{ $evento->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}</td>
                            <td>
                                @if ($evento->estado === 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Pasado</span>
                                @endif
                            </td>
                            <td>
                                @if (!is_null($evento->cupos))
                                    {{ $evento->cupos - $evento->cupos_disponibles }}
                                @else
                                    Ilimitado
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.evento.eliminar', $evento->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay eventos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
