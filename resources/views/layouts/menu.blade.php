
<li class="side-menus {{ Request::is('*') ? 'active' : '' }}" style="background:hsl(188, 100.00%, 25.10%)">
    
    <a class="nav-link" href="/home" style="background: #007080; color:white">
        <i class="fas fa-building"></i><span>Panel</span>
    </a>

    {{-- Usuarios solo para administrador y Super_Administrador --}}
    @role('Administrador|Super_Administrador')
    <a class="nav-link" href="/usuarios" style="background: #007080; color:white">
        <i class="fas fa-users"></i><span>Usuarios</span>
    </a>
    @endrole

    {{-- Roles solo para Super_Administrador --}}
    @role('Super_Administrador')
    <a class="nav-link" href="/roles" style="background: #007080; color:white">
        <i class="fas fa-user-lock"></i><span>Roles</span>
    </a>
    @endrole

    {{-- Auditoría solo para administrador y Super_Administrador --}}
    @role('Administrador|Super_Administrador')
    <a class="nav-link" href="/audits" style="background: #007080; color:white">
    <i class="fa-solid fa-clipboard-list"></i><span>Auditoría Usuarios</span>
    </a>
    @endrole

    {{-- Ticket para todos los roles --}}
    @role('Usuario|Soporte|Administrador|Super_Administrador')
    <a class="nav-link" href="/tickets" style="background: #007080; color:white">
        <i class="fa-solid fa-ticket-simple"></i><span>Ticket</span>
    </a>
    @endrole

    {{-- Ticket para todos los roles --}}
    @role('Administrador|Super_Administrador')
    <a class="nav-link" href="/settings" style="background: #007080; color:white">
    <i class="fa-solid fa-images" style="color: #ffffff;"></i><span>Banner</span>
    </a>
    @endrole

</li>
