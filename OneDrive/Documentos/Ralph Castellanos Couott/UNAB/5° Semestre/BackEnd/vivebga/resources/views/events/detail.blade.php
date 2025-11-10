@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 30px;">

        {{-- Botón volver --}}
        <a href="{{ route('main') }}" class="btn-volver">
            ← Volver al inicio
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

                // ¿ya está registrado el usuario?
                $yaRegistrado = \App\Models\EventRegistration::where('evento_id', $evento->id)
                    ->where('user_id', Auth::id())
                    ->exists();

                // Combinar fecha + hora y comparar en la zona America/Bogota
                $zona = new \Carbon\CarbonTimeZone('America/Bogota');
                $inicioEvento = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora, $zona);
                $ahora = \Carbon\Carbon::now($zona);

                // Bloquear registro si ya pasaron 15 minutos desde el inicio
                $bloquearRegistro = $ahora->gte($inicioEvento->copy()->addMinutes(15));

                // Bloquear cancelación si ya pasaron 15 minutos desde el inicio
                $bloquearCancelacion = $ahora->gte($inicioEvento->copy()->addMinutes(15));
            @endphp

            @if ($esCliente && !$esOrganizadorDelEvento)
                @if ($yaRegistrado)
                    {{-- Verificar si el evento ya comenzó hace más de 15 minutos --}}
                    @if ($bloquearCancelacion)
                        <p><strong style="color: red;">🚫 Ya no puedes cancelar la inscripción (el evento ya comenzó).</strong>
                        </p>
                    @else
                        <form action="{{ route('eventos.cancelar', $evento->id) }}" method="POST"
                            onsubmit="return confirm('¿Seguro que deseas cancelar tu registro a este evento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cancelar">Cancelar inscripción</button>
                        </form>
                    @endif
                @else
                    {{-- Verificar si el evento ya comenzó hace más de 15 minutos --}}
                    @if ($bloquearRegistro)
                        <p><strong style="color: red;">🚫 El registro está cerrado (el evento ya comenzó).</strong></p>
                    @else
                        {{-- Botón para registrarse (solo si hay cupos) --}}
                        @if (is_null($evento->cupos) || $cuposDisponibles > 0)
                            <button id="btn-registrar" class="btn-registrar">Registrarme al evento</button>
                        @else
                            <p><strong style="color: red;">🎟️ Entradas agotadas</strong></p>
                        @endif
                    @endif
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

        {{-- Botón para dejar reseña (solo si asistió y el evento ya ocurrió) --}}
        @php
            // Revisar si el usuario ya tiene reseña para este evento
            $reseñaExistente = \App\Models\EventRegistration::find($registroUsuario->id);
        @endphp

        @if ($usuarioAsistio && $eventoRealizado)
            <button id="btn-reseña" class="btn-reseña">
                {{ !empty($reseñaExistente->resena) ? 'Editar reseña' : 'Dejar reseña' }}
            </button>
        @endif

        {{-- ========================= --}}
        {{-- Sección de reseñas públicas --}}
        {{-- ========================= --}}
        @if ($resenas->count() > 0)
            <div class="reseñas-container" style="margin-top: 40px;">
                <h3>Reseñas de asistentes</h3>
                @foreach ($resenas as $resena)
                    <div class="reseña"
                        style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:10px;">
                        <p><strong>{{ $resena->user->name }}</strong> — {{ $resena->calificacion }} ⭐</p>
                        <p>{{ $resena->comentario }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p style="margin-top: 30px;">Aún no hay reseñas para este evento.</p>
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

                <form method="POST" action="{{ route('eventos.resena', $registroUsuario->id) }}">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">

                    <label for="calificacion">Calificación:</label>
                    <select name="calificacion" id="calificacion" required>
                        <option value="">Selecciona</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}"
                                {{ $reseñaExistente->calificacion == $i ? 'selected' : '' }}>
                                {{ $i }} ⭐
                            </option>
                        @endfor
                    </select>

                    <label for="resena">Comentario:</label>
                    <textarea name="resena" id="resena" rows="4" required>{{ $reseñaExistente->resena ?? '' }}</textarea>

                    <button type="submit">
                        {{ !empty($reseñaExistente->resena) ? 'Actualizar reseña' : 'Enviar reseña' }}
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Modal registro ---
            const modalRegistrar = document.getElementById("modal-registrar");
            const btnRegistrar = document.getElementById("btn-registrar");

            if (modalRegistrar && btnRegistrar) {
                const spanCerrar = modalRegistrar.querySelector(".close");
                btnRegistrar.addEventListener("click", () => modalRegistrar.style.display = "block");
                spanCerrar.addEventListener("click", () => modalRegistrar.style.display = "none");
                window.addEventListener("click", (e) => {
                    if (e.target === modalRegistrar) modalRegistrar.style.display = "none";
                });
            }

            // --- Actualizar total si es de pago ---
            const precio = {{ $evento->precio ?? 0 }};
            const cantidadInput = document.getElementById("cantidad");
            const totalSpan = document.getElementById("total");

            if (cantidadInput && totalSpan) {
                cantidadInput.addEventListener("input", function() {
                    let total = precio * this.value;
                    totalSpan.textContent = new Intl.NumberFormat('es-CO').format(total);
                });
            }

            // --- Modal reseña ---
            const modalReseña = document.getElementById("modal-reseña");
            const btnReseña = document.getElementById("btn-reseña");

            if (modalReseña && btnReseña) {
                const spanCerrarReseña = modalReseña.querySelector(".close");
                btnReseña.addEventListener("click", function() {
                    modalReseña.style.display = "block";
                });
                spanCerrarReseña.addEventListener("click", function() {
                    modalReseña.style.display = "none";
                });
                window.addEventListener("click", function(e) {
                    if (e.target === modalReseña) modalReseña.style.display = "none";
                });
            }
        });
    </script>
@endsection
