@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 30px;">

        {{-- Botón volver --}}
        <a href="{{ url()->previous() }}" class="btn-volver">
            ← Volver
        </a>

        {{-- Imagen del evento --}}
        <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->nombre }}"
            style="width:100%; height:400px; object-fit:cover; border-radius:10px; margin-bottom:20px;">

        {{-- Información general --}}
        <h1>{{ $evento->nombre }}</h1>
        <p>{{ $evento->descripcion }}</p>
        <p><strong>Fecha:</strong> {{ $evento->fecha }}</p>
        <p><strong>Hora:</strong> {{ $evento->hora }}</p>
        <p><strong>Ubicación:</strong> {{ $evento->direccion }}</p>
        <p><strong>Precio:</strong>
            @if ($evento->precio && $evento->precio > 0)
                ${{ number_format($evento->precio, 0, ',', '.') }}
            @else
                Gratis
            @endif
        </p>
        @if (!is_null($evento->cupos))
            @if ($cuposDisponibles > 0)
                <p><strong>Cupos disponibles:</strong> {{ $cuposDisponibles }}</p>
            @else
                <p><strong style="color: red;">🎟️ Entradas agotadas</strong></p>
            @endif
        @else
            <p><strong>Cupos:</strong> Sin límite</p>
        @endif

        {{-- Información del organizador --}}
        @if (isset($evento->user))
            <p><strong>Organizador:</strong>
                <a href="{{ route('organizer.profile', ['id' => $evento->user->id]) }}">
                    {{ $evento->user->name }} {{ $evento->user->apellido ?? '' }}
                </a>
            </p>
        @endif

        {{-- Botón para registrarse: solo usuarios logueados con rol "cliente" que no sean el organizador --}}
        @auth
            @php
                $esCliente = Auth::user()->role === 'cliente';
                $esOrganizadorDelEvento = Auth::id() === $evento->user->id;
            @endphp

            @if ($esCliente && !$esOrganizadorDelEvento)
                @if (is_null($evento->cupos) || $cuposDisponibles > 0)
                    <button id="btn-registrar" class="btn-registrar">Registrarme al evento</button>
                @else
                    <p><strong style="color: red;">🎟️ Entradas agotadas</strong></p>
                @endif
            @elseif($esOrganizadorDelEvento)
                <div class="alert alert-info mt-2">
                    <strong>Eres el organizador de este evento.</strong>
                </div>
            @else
                <div class="alert alert-warning mt-2">
                    <strong>Solo los clientes pueden registrarse a los eventos.</strong>
                </div>
            @endif
        @else
            <div class="mt-3">
                <a href="{{ route('login') }}" class="btn btn-secondary">Inicia sesión para registrarte</a>
            </div>
        @endauth

        {{-- Botón para dejar reseña (solo si asistió) --}}
        @if ($usuarioAsistio && $eventoRealizado)
            <button id="btn-reseña" class="btn-reseña">Dejar reseña</button>
        @endif

    </div>

    {{-- MODALES FUERA DEL CONTAINER --}}
    {{-- Modal de registro --}}
    <div id="modal-registrar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Registrarse al evento</h3>
            <form method="POST" action="{{ route('eventos.registrar', $evento->id) }}">
                @csrf
                <label for="cantidad">Cantidad de boletas:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" value="1" required>

                @if ($evento->precio && $evento->precio > 0)
                    <p>Precio por boleta: ${{ number_format($evento->precio, 0, ',', '.') }}</p>
                    <p>Total a pagar: $<span id="total">{{ number_format($evento->precio, 0, ',', '.') }}</span></p>
                @endif

                <button type="submit">Confirmar registro</button>
            </form>
        </div>
    </div>

    {{-- Modal de reseña: solo si el usuario asistió y el evento ya ocurrió --}}
    @if (!empty($usuarioAsistio) && !empty($eventoRealizado))
        <div id="modal-reseña" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Reseña del evento</h3>

                <form method="POST" action="{{ route('eventos.reseña', $registroUsuario->id) }}">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                    <label for="calificacion">Calificación:</label>
                    <select name="calificacion" id="calificacion" required>
                        <option value="">Selecciona</option>
                        <option value="1">1 ⭐</option>
                        <option value="2">2 ⭐</option>
                        <option value="3">3 ⭐</option>
                        <option value="4">4 ⭐</option>
                        <option value="5">5 ⭐</option>
                    </select>
                    <label for="comentario">Comentario:</label>
                    <textarea name="comentario" id="comentario" rows="4" required></textarea>
                    <button type="submit">Enviar reseña</button>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // Modal registro
        var modalRegistrar = document.getElementById("modal-registrar");
        var btnRegistrar = document.getElementById("btn-registrar");
        var spanCerrar = modalRegistrar.querySelector(".close");

        btnRegistrar.onclick = () => modalRegistrar.style.display = "block";
        spanCerrar.onclick = () => modalRegistrar.style.display = "none";
        window.onclick = (e) => {
            if (e.target == modalRegistrar) modalRegistrar.style.display = "none";
        }

        // Actualizar total si es de pago
        var precio = {{ $evento->precio ?? 0 }};
        var cantidadInput = document.getElementById("cantidad");
        var totalSpan = document.getElementById("total");
        if (cantidadInput && totalSpan) {
            cantidadInput.addEventListener("input", function() {
                let total = precio * this.value;
                totalSpan.textContent = new Intl.NumberFormat('es-CO').format(total);
            });
        }

        // Modal reseña (solo si existe en el DOM)
        @if (!empty($usuarioAsistio) && !empty($eventoRealizado))
            var modalReseña = document.getElementById("modal-reseña");
            var btnReseña = document.getElementById("btn-reseña");
            if (modalReseña && btnReseña) {
                var spanCerrarReseña = modalReseña.querySelector(".close");

                btnReseña.onclick = function() {
                    modalReseña.style.display = "block";
                };
                spanCerrarReseña.onclick = function() {
                    modalReseña.style.display = "none";
                };
                window.addEventListener('click', function(e) {
                    if (e.target == modalReseña) modalReseña.style.display = "none";
                });
            }
        @endif
    </script>
@endsection
