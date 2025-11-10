@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <!-- PANEL LATERAL -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center">
                    <img src="{{ asset($user->foto_perfil ?? 'images/default-avatar.png') }}" 
                        alt="Avatar" 
                        class="rounded-circle mb-3" 
                        width="120" height="120">
                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <p class="text-muted small">{{ $user->telefono ?? 'Sin teléfono registrado' }}</p>

                    <hr>
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.edit')  }}" class="btn btn-outline-primary btn-sm">
                            ✏️ Editar perfil
                        </a>
                        <a href="{{ route('user.events') }}" class="btn btn-outline-success btn-sm">
                            🎟️ Ver eventos
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm rounded-4">
                <div class="card-body text-center">
                    <h6 class="fw-bold text-secondary">Miembro desde</h6>
                    <p class="text-muted">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Información Personal</h4>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th class="text-muted">Nombre:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Correo electrónico:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Teléfono:</th>
                                <td>{{ $user->telefono ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Ciudad:</th>
                                <td>{{ $user->ciudad ?? 'No especificada' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
