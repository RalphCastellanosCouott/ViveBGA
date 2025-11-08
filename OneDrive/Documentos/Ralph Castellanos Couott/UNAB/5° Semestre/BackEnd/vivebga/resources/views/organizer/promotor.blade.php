@extends('layouts.app')

@section('content')
<div style="display: flex; min-height: 100vh;">

    <!-- Panel izquierdo -->
    <div style="width: 20%; background-color: #f5f5f5; padding: 20px; display: flex; flex-direction: column; align-items: center;">
        <div style="width: 100px; height: 100px; border-radius: 50%; background-color: #ddd; margin-bottom: 10px;"></div>
        <h3>{{ Auth::user()->name }}</h3>
        <ul style="list-style: none; padding: 0; text-align: center;">
            <li><a href="#">Calificaciones</a></li>
            <li><a href="#">Mis eventos</a></li>
            <li><a href="{{ route('mis-eventos') }}">Crear evento</a></li>
            <li><a href="#">Editar perfil</a></li>
        </ul>
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
           style="color: red; margin-top: 20px;">Cerrar Sesión</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

    <!-- Formulario -->
    <div style="flex-grow: 1; padding: 40px;">
        <h2>Publicar nuevo evento</h2>

        @if (session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

        <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nombre del evento:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <label>Hora:</label>
    <input type="time" name="hora" required>

    <label>Dirección:</label>
    <input type="text" name="direccion" required>

    <label>Precio:</label>
    <input type="number" name="precio" required>

    <label>Imagen del evento:</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit">Guardar evento</button>
</form>

    </div>
</div>
@endsection
