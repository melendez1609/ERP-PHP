export function initHoverSounds() {
    const hoverSound = new Audio('/sounds/button-hover.mp3');
    let isAudioUnlocked = false;

    const unlockAudio = () => {
        if (!isAudioUnlocked) {
            hoverSound.play().then(() => {
                hoverSound.pause();
                hoverSound.currentTime = 0;
                isAudioUnlocked = true;
            }).catch(() => {});
            
            document.removeEventListener('click', unlockAudio);
            document.removeEventListener('keydown', unlockAudio);
        }
    };

    document.addEventListener('click', unlockAudio);
    document.addEventListener('keydown', unlockAudio);

    const icons = document.querySelectorAll('.icon');
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', () => {
            if (isAudioUnlocked) {
                hoverSound.currentTime = 0;
                hoverSound.play().catch(e => console.log("Error al reproducir:", e));
            }
        });
    });
}