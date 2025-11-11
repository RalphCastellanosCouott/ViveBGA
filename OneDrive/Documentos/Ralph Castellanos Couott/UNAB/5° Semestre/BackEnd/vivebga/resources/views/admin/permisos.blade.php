@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Permisos de Organizadores</h2>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Permiso para Crear Eventos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($organizadores as $org)
                    <tr>
                        <td>{{ $org->id }}</td>
                        <td>{{ $org->name }} {{ $org->apellido }}</td>
                        <td>{{ $org->email }}</td>
                        <td>
                            @if ($org->permiso_evento)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.organizador.permiso', $org->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    @if ($org->permiso_evento)
                                        Revocar
                                    @else
                                        Otorgar
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay organizadores registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
