export function initPurchaseOrderActions() {
    let activeProductsCache = null;
    let editEventsInitialized = false;

    async function getActiveProducts(forceRefresh = false) {
        if (
            !forceRefresh &&
            Array.isArray(activeProductsCache) &&
            activeProductsCache.length > 0
        ) {
            return activeProductsCache;
        }

        try {
            const response = await fetch('/inventory/active-products', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
                cache: 'no-store',
            });

            if (!response.ok) {
                throw new Error(
                    `No se pudieron obtener los productos. Código: ${response.status}`
                );
            }

            const payload = await response.json();

            let products = [];

            if (Array.isArray(payload)) {
                products = payload;
            } else if (Array.isArray(payload.data)) {
                products = payload.data;
            } else if (Array.isArray(payload.products)) {
                products = payload.products;
            }

            activeProductsCache = products;

            return products;
        } catch (error) {
            console.error('Error al obtener productos activos:', error);
            activeProductsCache = [];
            return [];
        }
    }

    function setupEditModalListeners() {
        if (editEventsInitialized) return;

        const btnAdd = document.getElementById('btn-add-edit-po-product');
        if (btnAdd) {
            btnAdd.addEventListener('click', async () => {
                const products = await getActiveProducts();
                appendEditProductRow(products);
            });
        }

        const tbody = document.getElementById('edit-po-products-body');
        if (tbody) {
            tbody.addEventListener('change', async (e) => {
                const productSelect = e.target.closest('.po-edit-product-select');
                if (!productSelect) return;

                const row = productSelect.closest('tr');
                if (!row) return;

                const costInput = row.querySelector('.po-edit-cost');
                const selectedProductId = productSelect.value;

                if (!selectedProductId) {
                    if (costInput) costInput.value = '0.00';
                    updateRowSubtotal(row);
                    calculateEditTotals();
                    return;
                }

                const products = await getActiveProducts(true);
                const product = products.find(
                    item => String(item.id) === String(selectedProductId)
                );

                if (!product) {
                    if (costInput) costInput.value = '0.00';
                    updateRowSubtotal(row);
                    calculateEditTotals();
                    return;
                }

                const rawCost = product.cost ?? product.purchase_cost ?? 0;
                const productCost = Number(rawCost);

                if (costInput && Number.isFinite(productCost)) {
                    costInput.value = productCost.toFixed(2);
                }

                updateRowSubtotal(row);
                calculateEditTotals();
            });

            tbody.addEventListener('input', (e) => {
                if (e.target.classList.contains('po-edit-cost') || e.target.classList.contains('po-edit-qty')) {
                    const row = e.target.closest('tr');
                    updateRowSubtotal(row);
                    calculateEditTotals();
                }
            });

            tbody.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.po-edit-remove-row');
                if (removeBtn) {
                    if (tbody.children.length > 1) {
                        removeBtn.closest('tr').remove();
                        reindexEditRows();
                        calculateEditTotals();
                    } else {
                        alert('La orden de compra debe incluir al menos un producto.');
                    }
                }
            });
        }

        editEventsInitialized = true;
    }

    function updateRowSubtotal(row) {
        const cost = parseFloat(row.querySelector('.po-edit-cost')?.value) || 0;
        const qty = parseInt(row.querySelector('.po-edit-qty')?.value) || 0;
        const subtotal = cost * qty;
        const cell = row.querySelector('.po-edit-subtotal-cell');
        if (cell) cell.textContent = `$${subtotal.toFixed(2)}`;
    }

    function calculateEditTotals() {
        const tbody = document.getElementById('edit-po-products-body');
        if (!tbody) return;

        let grandTotal = 0;
        tbody.querySelectorAll('tr').forEach(tr => {
            const cost = parseFloat(tr.querySelector('.po-edit-cost')?.value) || 0;
            const qty = parseInt(tr.querySelector('.po-edit-qty')?.value) || 0;
            grandTotal += (cost * qty);
        });

        const formatted = grandTotal.toFixed(2);
        const subtotalText = document.getElementById('edit-po-subtotal-text');
        const totalText = document.getElementById('edit-po-total-text');
        const subtotalInput = document.getElementById('edit_po_subtotal_input');
        const totalInput = document.getElementById('edit_po_total_input');

        if (subtotalText) subtotalText.textContent = formatted;
        if (totalText) totalText.textContent = formatted;
        if (subtotalInput) subtotalInput.value = formatted;
        if (totalInput) totalInput.value = formatted;
    }

    function reindexEditRows() {
        const tbody = document.getElementById('edit-po-products-body');
        if (!tbody) return;
        Array.from(tbody.children).forEach((tr, index) => {
            const pSelect = tr.querySelector('.po-edit-product-select');
            const cInput = tr.querySelector('.po-edit-cost');
            const qInput = tr.querySelector('.po-edit-qty');

            if (pSelect) pSelect.name = `products[${index}][product_id]`;
            if (cInput) cInput.name = `products[${index}][cost]`;
            if (qInput) qInput.name = `products[${index}][quantity]`;
        });
    }

    function appendEditProductRow(activeProducts, productData = null) {
        const tbody = document.getElementById('edit-po-products-body');
        if (!tbody) return;

        const index = tbody.children.length;
        const tr = document.createElement('tr');

        const savedProductId = productData ? (productData.product_id || productData.id) : null;

        let optionsHtml = '<option value="">-- Seleccionar Producto --</option>';
        activeProducts.forEach(p => {
            const selected = savedProductId && String(savedProductId) === String(p.id) ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" ${selected}>[${p.code}] ${p.name}</option>`;
        });

        const cost = productData ? parseFloat(productData.cost ?? productData.purchase_cost ?? 0).toFixed(2) : '0.00';
        const qty = productData ? (productData.quantity || 1) : 1;
        const subtotal = (parseFloat(cost) * parseInt(qty)).toFixed(2);

        tr.innerHTML = `
            <td>
                <select name="products[${index}][product_id]" class="po-edit-product-select" required style="width: 100%;">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="products[${index}][cost]" class="po-edit-cost" value="${cost}" required style="width: 90px;">
            </td>
            <td>
                <input type="number" min="1" name="products[${index}][quantity]" class="po-edit-qty" value="${qty}" required style="width: 70px;">
            </td>
            <td class="po-edit-subtotal-cell">$${subtotal}</td>
            <td style="text-align: center;">
                <button type="button" class="inventory-table-button delete po-edit-remove-row" style="padding: 4px 8px;">&times;</button>
            </td>
        `;

        tbody.appendChild(tr);
    }

    async function handleEditAction(selectedOption) {
        setupEditModalListeners();

        const modal = document.getElementById('modal-edit-purchase-order');
        if (!modal) return;

        const form = document.getElementById('edit-purchase-order-form');
        const supplierSelect = document.getElementById('edit_supplier_id');
        const tbody = document.getElementById('edit-po-products-body');

        const updateUrl = selectedOption.dataset.url;
        const supplierId = selectedOption.dataset.supplierId;
        const rawProducts = selectedOption.dataset.products;

        let products = [];
        try {
            products = typeof rawProducts === 'string' ? JSON.parse(rawProducts) : (rawProducts || []);
        } catch (e) {
            console.error('Error parseando JSON de productos:', e);
        }

        if (form && updateUrl) {
            form.action = updateUrl;
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
        }

        if (supplierSelect && supplierId) {
            supplierSelect.value = supplierId;
        }

        activeProductsCache = null;
        const activeProducts = await getActiveProducts(true);

        if (tbody) {
            tbody.innerHTML = '';
            if (products.length === 0) {
                appendEditProductRow(activeProducts);
            } else {
                products.forEach(p => {
                    const productId = p.product_id || p.id;
                    const matchingActive = activeProducts.find(
                        item => String(item.id) === String(productId)
                    );
                    if (matchingActive) {
                        const currentCost = matchingActive.cost ?? matchingActive.purchase_cost;
                        if (currentCost !== undefined) {
                            p.cost = currentCost; // Se actualiza al precio actual del inventario
                        }
                    }
                    appendEditProductRow(activeProducts, p);
                });
            }
        }

        calculateEditTotals();

        modal.style.display = 'flex';
        modal.classList.add('show');
    }

    function resetSelect(select) {
        select.selectedIndex = 0;
        select.value = '';
        select.blur();
    }

    document.addEventListener('change', async (event) => {
        if (!event.target.classList.contains('po-action-select')) return;

        const select = event.target;
        const actionType = select.value;
        if (!actionType) return;

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        if (actionType === 'pdf') {
            const pdfUrl = selectedOption.dataset.pdfUrl;
            if (pdfUrl && pdfUrl !== '#') {
                window.open(pdfUrl, '_blank');
            }
            resetSelect(select);
            return;
        }

        if (actionType === 'edit') {
            await handleEditAction(selectedOption);
            resetSelect(select);
            return;
        }

        const modalTarget = selectedOption.dataset.modalTarget || 'modal-alert';
        const action = selectedOption.dataset.action;
        const method = selectedOption.dataset.method || 'POST';
        const titleText = selectedOption.dataset.title;
        const messageText = selectedOption.dataset.message;
        const btnText = selectedOption.dataset.btnText;
        const btnClass = selectedOption.dataset.btnClass;

        resetSelect(select);

        const modal = document.getElementById(modalTarget);
        if (!modal) {
            console.error(`No se encontró el modal: ${modalTarget}`);
            return;
        }

        const form = modal.querySelector('form');

        if (form && action && action !== '#') {
            form.action = action;

            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = method;
        }

        const title = modal.querySelector('.modal-title') || modal.querySelector('h3, h4, .title');
        const message = modal.querySelector('.modal-message') || modal.querySelector('p, .message');
        const btnSubmit = modal.querySelector('button[type="submit"]') || modal.querySelector('.btn-confirm');

        if (title && titleText) title.textContent = titleText;
        if (message && messageText) message.textContent = messageText;

        if (btnSubmit) {
            if (btnText) btnSubmit.textContent = btnText;
            if (btnClass) btnSubmit.className = `btn ${btnClass}`;
        }

        modal.style.display = 'flex';
        modal.classList.add('show');
    });

    document.addEventListener('click', (event) => {
        const closeBtn = event.target.closest('.btn-cancel, .close, .close-modal, [data-dismiss="modal"], [data-bs-dismiss="modal"], [data-modal-close]');
        if (!closeBtn) return;

        const modal = closeBtn.closest('.modal, #modal-alert, #modal-edit-purchase-order');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }

        document.querySelectorAll('.po-action-select').forEach(resetSelect);
    });
}