import { initModals } from './functionalities/modal.js';
import { initHoverSounds } from './functionalities/sound.js';
import { initDateTime } from './functionalities/datetime.js';
import { initAlertModal } from './functionalities/alert.js';

import { initEditInventory } from './actions/inventory/EditInventory.js';
import { initEditUser } from './actions/users/EditUser.js';
import { initEditSupplier } from './actions/supplier/EditSupplier.js';

document.addEventListener('DOMContentLoaded', () => {
    initHoverSounds();
    initDateTime();
    initModals();
    initAlertModal();

    initEditInventory();
    initEditSupplier();
    initEditUser();
});