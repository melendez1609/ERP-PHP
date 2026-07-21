<footer class="footer-main-container">
    <div class="footer-welcome">
        @auth
            Bienvenido, {{ auth()->user()->name }}
        @endauth
    </div> 
    <div class="footer-datetime">
        <span></span> 
        <span></span> 
    </div>
</footer>