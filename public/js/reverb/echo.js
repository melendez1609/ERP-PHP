export function initEcho() {
    if (typeof Pusher !== 'undefined' && typeof window.Echo === 'function') {
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: 'k9dpnw2a7v2zb06gsn1v',
            wsHost: '127.0.0.1',
            wsPort: 8080,
            wssPort: 8080,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        console.log('🟢 Laravel Echo instanciado correctamente con Reverb');
    }
}