<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Pantalla de Bloqueo</title>
    <link rel="preload" as="image" href="{{ asset('images/lockscreen-image.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    <div class="lockscreen-main-container">
        <div class="lockscreen-section-left">
            <div class="lockscreen-header-logo">
                <img src="{{ asset('images/logo.png') }}" alt="G-ERP" class="lockscreen-logo-img">
            </div>

            <div class="lockscreen-card">
                <h4 class="lockscreen-tittle">Pantalla de Bloqueo</h4>
                
                <div class="lockscreen-user-info">
                    @php
                        $avatarSrc = asset('icons/user.png');
                        if (auth()->check() && !empty(auth()->user()->image)) {
                            $imagePath = storage_path('app/public/' . auth()->user()->image);
                            if (file_exists($imagePath)) {
                                $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                                $data = file_get_contents($imagePath);
                                $avatarSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        }
                    @endphp

                    <img src="{{ $avatarSrc }}" class="lockscreen-avatar" alt="{{ auth()->user()->name ?? 'Usuario' }}">
                    <h5 class="lockscreen-username">{{ auth()->user()->name ?? 'Usuario del Sistema' }}</h5>
                </div>

                <form action="{{ route('lockscreen.unlock') }}" method="POST" class="lockscreen-form">
                    @csrf

                    @error('password')
                        <div class="alert-error" style="margin-bottom: 10px;">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-group">
                        <input type="password" name="password" class="credentials-input" placeholder="Ingrese su contraseña" required autofocus>
                    </div>

                    <button type="submit" class="submit-input lockscreen-btn">Desbloquear</button>

                    <div class="lockscreen-footer-link">
                        <p>¿No eres tú? 
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Iniciar sesión
                            </a>
                        </p>
                    </div>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <div class="lockscreen-section-right"></div>
    </div>

    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>
</html>