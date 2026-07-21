<header class="header-main-container">
    <img class="logo-header" src="{{ asset('images/logo.png') }}" alt="logo-header">
    @auth
        <h2 class="logo-tittle">Workspace</h2>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <img class="logout-session" 
         src="{{ asset('icons/logout.png') }}" 
         alt="logout" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    @endauth
</header>
