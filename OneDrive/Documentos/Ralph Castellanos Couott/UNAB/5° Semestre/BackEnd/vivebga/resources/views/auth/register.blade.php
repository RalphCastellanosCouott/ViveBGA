@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Rol --}}
                            <div class="row mb-3">
                                <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Rol') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select id="role" class="form-control @error('role') is-invalid @enderror"
                                        name="role" required>
                                        <option value="cliente" {{ old('role') == 'cliente' ? 'selected' : '' }}>Cliente
                                        </option>
                                        <option value="organizador" {{ old('role') == 'organizador' ? 'selected' : '' }}>
                                            Organizador</option>
                                    </select>

                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Nombre --}}
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Apellido (Cliente) --}}
                            <div class="row mb-3" id="apellido-field">
                                <label for="apellido" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Apellido') }}
                                    <span class="text-danger" id="apellido-asterisk" style="display:none">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input id="apellido" type="text"
                                        class="form-control @error('apellido') is-invalid @enderror" name="apellido"
                                        value="{{ old('apellido') }}">
                                    @error('apellido')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Descripción (Organizador) --}}
                            <div class="row mb-3" id="descripcion-field">
                                <label for="descripcion" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Descripción') }}
                                    <span class="text-danger" id="descripcion-asterisk" style="display:none">*</span>
                                </label>
                                <div class="col-md-6">
                                    <textarea id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Foto de perfil --}}
                            <div class="row mb-3">
                                <label for="foto_perfil" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Foto de perfil') }}
                                    <span class="text-danger" id="foto-asterisk" style="display:none">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input id="foto_perfil" type="file"
                                        class="form-control @error('foto_perfil') is-invalid @enderror" name="foto_perfil">
                                    @error('foto_perfil')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="row mb-3">
                                <label for="password_confirmation"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const apellidoField = document.getElementById('apellido-field');
        const descripcionField = document.getElementById('descripcion-field');
        const fotoField = document.getElementById('foto_perfil');

        function toggleFields() {
            if (roleSelect.value === 'cliente') {
                apellidoField.style.display = 'flex';
                descripcionField.style.display = 'none';
                fotoField.required = false;

                // Asteriscos
                document.getElementById('apellido-asterisk').style.display = 'inline'; // obligatorio para clientes
                document.getElementById('descripcion-asterisk').style.display = 'none';
                document.getElementById('foto-asterisk').style.display = 'none';
            } else if (roleSelect.value === 'organizador') {
                apellidoField.style.display = 'none';
                descripcionField.style.display = 'flex';
                fotoField.required = true;

                // Asteriscos
                document.getElementById('apellido-asterisk').style.display = 'none';
                document.getElementById('descripcion-asterisk').style.display = 'inline';
                document.getElementById('foto-asterisk').style.display = 'inline';
            }
        }

        // Inicializa al cargar la página
        toggleFields();

        // Escucha cambios en el select
        roleSelect.addEventListener('change', toggleFields);
    </script>
@endsection
