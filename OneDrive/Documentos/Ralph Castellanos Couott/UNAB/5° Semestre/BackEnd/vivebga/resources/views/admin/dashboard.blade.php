@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Barra lateral -->
            <div class="col-md-3 col-lg-2 bg-dark text-white vh-100 p-3">
                <h4 class="mb-4">Panel Admin</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link text-white">Clientes</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Organizadores</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Eventos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Permisos</a></li>
                </ul>
            </div>

            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 p-5">
                <h2 class="mb-4">Resumen General</h2>

                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Clientes</h5>
                                <p class="display-6">{{ $clientes }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-success">
                            <div class="card-body">
                                <h5 class="card-title">Organizadores</h5>
                                <p class="display-6">{{ $organizadores }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h5 class="card-title">Eventos Activos</h5>
                                <p class="display-6">{{ $eventosActivos }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-info">
                            <div class="card-body">
                                <h5 class="card-title">Total Eventos</h5>
                                <p class="display-6">{{ $totalEventos }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
