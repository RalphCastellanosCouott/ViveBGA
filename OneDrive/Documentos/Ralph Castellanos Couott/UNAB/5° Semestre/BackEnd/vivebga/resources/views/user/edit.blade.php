@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0">✏️ Editar Perfil</h5>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.update') }}" method="POST">
                     @csrf
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Correo</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" class="form-control">
                    </div>

                     <div class="form-group">
                        <label>Ciudad</label>
                         <input type="text" name="ciudad" value="{{ old('ciudad', $user->ciudad) }}" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
