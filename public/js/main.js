import { initModals } from './modal.js';
import { initHoverSounds } from './sound.js';
import { initDateTime } from './datetime.js';

document.addEventListener('DOMContentLoaded', () => {
    initHoverSounds();
    initDateTime();
});

document.addEventListener('DOMContentLoaded', () => {
    initModals();
});