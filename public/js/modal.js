export function initModals() {

    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const targetId = trigger.getAttribute('data-modal-target');
            const modal = document.getElementById(targetId);
            if (modal) modal.style.display = 'flex';
        });
    });

    document.querySelectorAll('.modal').forEach(modal => {
        const closeTriggers = modal.querySelectorAll('[data-modal-close]');
        
        closeTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });
    });
}