<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | G-ERP</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
</head>
    @include('partials.header')
    <main class="login-main-container">
        <section class="login-section-top">
            <img class="login-logo" src="{{ asset('images/logo.png') }}" alt="logo-header">
        </section>
        <section class="login-input-container">
            <section class="login-section-left">
                <h1 class="login-tittle">Bienvenido a G-ERP</h1>
                <h2 class="login-sub-tittle-1">Sistema de Gestión Empresarial y Punto de Venta</h2>
                <h2 class="login-sub-tittle-2">Para soporte, por favor contáctenos en support@gerp.com</h2>
            </section>
            <form class="login-section-right" action="{{ route('login.post') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif
                <input class="credentials-input" type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required><br><br>
                <input class="credentials-input" type="password" name="password" placeholder="Contraseña" required><br><br>
                <input class="submit-input" type="submit" value="Iniciar sesión">
                <p class="login-disclaimer"><b>Importante:</b> Sus credenciales son personales y confidenciales. No las comparta. Para reportar incidencias, contacte al área de soporte.</p>
            </form>
        </section>
    </main>
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>