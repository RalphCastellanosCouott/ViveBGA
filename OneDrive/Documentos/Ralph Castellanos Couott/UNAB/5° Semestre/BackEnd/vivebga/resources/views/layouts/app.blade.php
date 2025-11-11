<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ViveBGA') }}</title>

    <!-- Fuentes -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('css')
</head>

<body>
    <div id="app">
        @if (Auth::check() && Auth::user()->role === 'admin')
            @include('layouts.nav_admin')
        @else
            @include('layouts.navbar')
        @endif

        <main class="py-4">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    @yield('scripts')

    {{-- 🔔 Modal de alerta dinámico --}}
    @if (session('error') || session('success'))
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header {{ session('error') ? 'bg-danger text-white' : 'bg-success text-white' }}">
                        <h5 class="modal-title" id="alertModalLabel">
                            {{ session('error') ? 'Error' : 'Éxito' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        {{ session('error') ?? session('success') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('alertModal');
                if (modalElement) {
                    const alertModal = new bootstrap.Modal(modalElement);
                    alertModal.show();
                }
            });
        </script>
    @endif
</body>

</html>
