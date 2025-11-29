<aside id="sidebar-wrapper" height="500" style="background: #007080">
    <div class="sidebar-brand" height="500">
        <img class="navbar-brand-full app-header-logo m-2" src="{{ asset('img/logo.png') }}" width="200"
             alt="Infyom Logo">
        <a href="{{ url('/') }}"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{ asset('img/logo.png') }}" width="45px" alt=""/>
        </a>
    </div>
    <ul class="sidebar-menu m-2" >
        @include('layouts.menu')
    </ul>
</aside>
