export function initUserStatusMonitor() {
    const currentPath = window.location.pathname;
    if (currentPath === '/login' || currentPath === '/') {
        return;
    }

    setInterval(async () => {
        try {
            const response = await fetch('/check-status', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.status === 401) {
                window.location.href = '/login';
            }
        } catch (error) {
            console.error('Error verificando el estado de la sesión:', error);
        }
    }, 4000);
}