<header class="header-main-container">
    <div class="header-section-left">
        <img class="logo-header" src="{{ asset('images/logo.png') }}" alt="logo-header">
        @auth
            <h2 class="logo-tittle">Workspace</h2>
            <h2 class="rol-name">{{ auth()->user()->role }}</h2>
        @endauth
    </div>
    @auth
        <div class="header-section-right">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @unless(request()->routeIs('dashboard'))
                <a class="go-back-option" href="{{ route('dashboard') }}">
                    <img class="go-back-icon" src="{{ asset('icons/control-panel.png') }}" alt="control-panel">
                </a>
            @endunless
            <img class="logout-session" src="{{ asset('icons/logout.png') }}" alt="logout" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        </div>
    @endauth
</header>
