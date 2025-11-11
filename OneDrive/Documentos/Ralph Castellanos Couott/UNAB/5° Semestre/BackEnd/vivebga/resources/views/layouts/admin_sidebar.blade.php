<div class="col-md-3 col-lg-2 bg-dark text-white vh-100 p-3">
    <h4 class="mb-4">Panel Admin</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Inicio</a></li>
        <li class="nav-item"><a href="{{ route('admin.clientes') }}" class="nav-link text-white">Clientes</a></li>
        <li class="nav-item"><a href="{{ route('admin.organizadores') }}" class="nav-link text-white">Organizadores</a></li>
        <li class="nav-item"><a href="{{ route('admin.eventos') }}" class="nav-link text-white">Eventos</a></li>
        <li class="nav-item"><a href="{{ route('admin.permisos') }}" class="nav-link text-white">Permisos</a></li>
    </ul>
</div>