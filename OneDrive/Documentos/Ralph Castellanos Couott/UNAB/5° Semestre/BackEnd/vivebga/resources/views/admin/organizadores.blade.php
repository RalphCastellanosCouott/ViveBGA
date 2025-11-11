@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 p-5">
                <h2 class="mb-4">Lista de Organizadores</h2>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Email</th>
                            <th>Fecha de Registro</th>
                            <th>Permiso para Crear Eventos</th>
                            <th>Cantidad de Eventos</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizadores as $organizador)
                            <tr>
                                <td>{{ $organizador->id }}</td>
                                <td>{{ $organizador->name }}</td>
                                <td>{{ $organizador->descripcion ?? 'Sin descripción' }}</td>
                                <td>{{ $organizador->email }}</td>
                                <td>{{ $organizador->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($organizador->puede_crear_eventos)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </td>
                                <td>{{ $organizador->eventos_count }}</td>
                                <td>
                                    <form action="{{ route('admin.usuario.eliminar', $organizador->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este organizador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay organizadores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
