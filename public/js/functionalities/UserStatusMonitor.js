export function initUserStatusMonitor() {
    const currentPath = window.location.pathname;
    if (currentPath === '/login' || currentPath === '/') {
        return;
    }

    const metaUserId = document.querySelector('meta[name="user-id"]')?.content;
    const modalSchedule = document.getElementById('modal-schedule');
    
    const authUserId = metaUserId 
        ? parseInt(metaUserId) 
        : (modalSchedule?.dataset?.userId ? parseInt(modalSchedule.dataset.userId) : null);

    if (window.Echo) {
        window.Echo.channel('user-status-channel')
            .listen('.UserStatusBroadcast', (e) => {
                if (authUserId && e.userId === authUserId) {
                    if (e.status === 'logout' || e.status === 'inactive') {
                        console.warn('🔒 Sesión finalizada o usuario desactivado.');
                        window.location.href = '/login';
                    } else if (e.status === 'role_updated') {
                        console.info('🔄 Tu rol ha sido actualizado. Recargando interfaz...');
                        window.location.reload();
                    }
                }
            });
    }
}