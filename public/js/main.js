import { initModals } from './functionalities/modal.js';
import { initHoverSounds } from './functionalities/sound.js';
import { initDateTime } from './functionalities/datetime.js';
import { initAlertModal } from './functionalities/alert.js';
import { initSelectFinder } from './functionalities/finder.js';

import { initEditInventory } from './actions/inventory/EditInventory.js';
import { initEditUser } from './actions/users/EditUser.js';
import { initEditSupplier } from './actions/supplier/EditSupplier.js';

import { initCreateQuotation } from './actions/quotation/CreateQuotation.js';
import { initCreatePurchaseOrder } from './actions/purchase-order/CreatePurchaseOrder.js';

document.addEventListener('DOMContentLoaded', () => {
    initHoverSounds();
    initDateTime();
    initModals();
    initAlertModal();

    initEditInventory();
    initEditSupplier();
    initEditUser();

    initCreateQuotation();
    initCreatePurchaseOrder();

    initSelectFinder('product-search', 'select-product');
    initSelectFinder('supplier-search', 'supplier_id');
    initSelectFinder('po-product-search', 'po-select-product');
});