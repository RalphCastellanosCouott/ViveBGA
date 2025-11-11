@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 p-5">
                <h2 class="mb-4">Lista de Clientes</h2>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de Registro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->name }} {{ $cliente->apellido }}</td>
                                <td>{{ $cliente->email }}</td>
                                <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.usuario.eliminar', $cliente->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
