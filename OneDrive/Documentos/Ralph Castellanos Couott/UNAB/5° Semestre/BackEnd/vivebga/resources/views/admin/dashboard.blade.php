@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            
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
