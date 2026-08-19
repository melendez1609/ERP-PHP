export function initHoverSounds() {
    const hoverSound = new Audio('/sounds/button-hover.mp3');
    let isAudioUnlocked = false;

    const unlockAudio = () => {
        if (!isAudioUnlocked) {
            hoverSound.play().then(() => {
                hoverSound.pause();
                hoverSound.currentTime = 0;
                isAudioUnlocked = true;
                cleanListeners();
            }).catch(() => {});
        }
    };

    const cleanListeners = () => {
        document.removeEventListener('click', unlockAudio);
        document.removeEventListener('keydown', unlockAudio);
        document.removeEventListener('pointerdown', unlockAudio);
        document.removeEventListener('mousemove', unlockAudio);
    };

    document.addEventListener('click', unlockAudio);
    document.addEventListener('keydown', unlockAudio);
    document.addEventListener('pointerdown', unlockAudio);
    document.addEventListener('mousemove', unlockAudio, { once: true });

    const interactiveElements = document.querySelectorAll('.icon, .header-icon, .volume-icon');

    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            if (!isAudioUnlocked) {
                unlockAudio();
            }
            hoverSound.currentTime = 0;
            hoverSound.play().catch(() => {});
        });
    });
}