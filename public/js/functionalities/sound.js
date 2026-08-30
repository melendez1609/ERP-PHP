function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function setCookie(name, value, days = 30) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${d.toUTCString()};path=/`;
}

export function initHoverSounds() {
    const hoverSound = new Audio('/sounds/button-hover.mp3');

    const savedVolume = getCookie('sound_volume');
    let soundVolume = savedVolume !== null ? parseFloat(savedVolume) : 1.0;
    hoverSound.volume = soundVolume;

    let isAudioUnlocked = false;

    const savedSoundState = getCookie('sound_enabled');
    let soundEnabled = savedSoundState !== 'false';

    const volumeControl = document.querySelector('.volume-control');
    const volumeIcon = document.querySelector('.volume-icon');
    const volumeRange = document.getElementById('volume-range');
    const volumePercentage = document.getElementById('volume-percentage');

    const updateUI = () => {
        if (volumeIcon) {
            volumeIcon.src = soundEnabled ? '/icons/audio.png' : '/icons/no-audio.png';
        }
        if (volumeRange) {
            volumeRange.value = Math.round(soundVolume * 100);
        }
        if (volumePercentage) {
            volumePercentage.textContent = `${Math.round(soundVolume * 100)}%`;
        }
    };

    updateUI();

    if (volumeRange) {
        volumeRange.addEventListener('input', (e) => {
            const val = parseInt(e.target.value, 10);
            soundVolume = val / 100;
            hoverSound.volume = soundVolume;

            if (volumePercentage) {
                volumePercentage.textContent = `${val}%`;
            }

            setCookie('sound_volume', soundVolume.toString());
            console.log('🔊 [Cookie Updated] "sound_volume":', soundVolume);

            if (val > 0 && !soundEnabled) {
                soundEnabled = true;
                setCookie('sound_enabled', 'true');
                updateUI();
            } else if (val === 0 && soundEnabled) {
                soundEnabled = false;
                setCookie('sound_enabled', 'false');
                updateUI();
            }
        });
    }

    const unlockAudio = () => {
        if (!isAudioUnlocked && soundEnabled) {
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

    if (volumeControl) {
        volumeControl.addEventListener('click', (e) => {
            e.stopPropagation();

            soundEnabled = !soundEnabled;
            setCookie('sound_enabled', soundEnabled ? 'true' : 'false');
            updateUI();

            if (soundEnabled) {
                hoverSound.play().then(() => {
                    hoverSound.pause();
                    hoverSound.currentTime = 0;
                    isAudioUnlocked = true;
                }).catch(() => {});
            }
        });
    }

    const interactiveElements = document.querySelectorAll('.icon, .header-icon, .volume-icon');

    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', () => {
            if (!soundEnabled || soundVolume === 0) return;

            if (!isAudioUnlocked) {
                unlockAudio();
            }

            hoverSound.currentTime = 0;
            hoverSound.play().catch(() => {});
        });
    });
}