@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Lista de eventos de {{ $user->name }}</h1>

    @if($eventos->isEmpty())
        <p>No hay eventos registrados.</p>
    @else
        @foreach($eventos as $evento)
            <div style="margin-bottom:20px; padding:10px; border:1px solid #ccc;">
                <h2>{{ $evento->nombre }}</h2>
                <p><strong>Descripción:</strong> {{ $evento->descripcion }}</p>
                <p><strong>Fecha:</strong> {{ $evento->fecha }}</p>
                <p><strong>Hora:</strong> {{ $evento->hora }}</p>
                <p><strong>Precio:</strong> ${{ $evento->precio }}</p>
                <p><strong>Dirección:</strong> {{ $evento->direccion }}</p>

                <form action="{{ url('/eventos/'.$evento->id) }}" method="get">
                    <button type="submit">Ver evento</button>
                </form>
            </div>
        @endforeach
    @endif

</div>
@endsection
