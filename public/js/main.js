import { initModals } from './functionalities/modal.js';
import { initHoverSounds } from './functionalities/sound.js';
import { initDateTime } from './functionalities/datetime.js';

import { initEditInventory } from './actions/inventory/EditInventory.js';
import { initDeleteInventory } from './actions/inventory/DeleteInventory.js';
import { initDisableInventory } from './actions/inventory/DisableInventory.js';

import { initEditUser } from './actions/users/EditUser.js';
import { initDeleteUser } from './actions/users/DeleteUser.js';
import { initDisableUser } from './actions/users/DisableUser.js';

document.addEventListener('DOMContentLoaded', () => {
    initHoverSounds();
    initDateTime();
    initModals();

    initEditInventory();
    initDeleteInventory();
    initDisableInventory();

    initEditUser();
    initDeleteUser();
    initDisableUser();
});