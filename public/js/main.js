import { initModals } from './functionalities/modal.js';
import { initHoverSounds } from './functionalities/sound.js';
import { initDateTime } from './functionalities/datetime.js';
import { initAlertModal } from './functionalities/alert.js';
import { initSelectFinder } from './functionalities/finder.js';

import { initAddInventory } from './actions/inventory/AddInventory.js';
import { initEditInventory } from './actions/inventory/EditInventory.js';
import { initBatchesInventory } from './actions/inventory/BatchesInventory.js';

import { initEditUser } from './actions/users/EditUser.js';
import { initStatusUser } from './actions/users/StatusUser.js';

import { initEditSupplier } from './actions/supplier/EditSupplier.js';

import { initCreateQuotation } from './actions/quotation/CreateQuotation.js';

import { initCreatePurchaseOrder } from './actions/purchase-order/CreatePurchaseOrder.js';
import { initPurchaseOrderActions } from './actions/purchase-order/PurchaseOrderActions.js';

import { initVatSet } from './actions/vat/VatSet.js';

import { initUserStatusMonitor } from './functionalities/UserStatusMonitor.js';

import { initBarcodes } from './actions/barcodes/barcodes.js';

import { initScheduleDate } from './actions/schedule/ScheduleDate.js';

document.addEventListener('DOMContentLoaded', () => {
    initHoverSounds();
    initDateTime();
    initModals();
    initAlertModal();
    initAddInventory();
    initEditInventory();
    initBatchesInventory();
    initEditSupplier();
    initEditUser();
    initStatusUser();
    initCreateQuotation();
    initCreatePurchaseOrder();
    initPurchaseOrderActions();
    initVatSet();
    initUserStatusMonitor();
    initBarcodes();
    initScheduleDate();

    initSelectFinder('product-search', 'select-product');
    initSelectFinder('supplier-search', 'supplier_id');
    initSelectFinder('po-product-search', 'po-select-product');
    
});