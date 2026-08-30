export function initCreatePurchaseOrder() {
    const supplierSelect = document.getElementById('po-supplier-id');
    const productSelect = document.getElementById('po-select-product');
    const productSearchInput = document.getElementById('po-product-search');
    const quantityInput = document.getElementById('po-input-quantity');
    const btnAddProduct = document.getElementById('po-btn-add-product');
    const poItemsBody = document.getElementById('po-items-body');
    const poTotalSpan = document.getElementById('po-total');
    const formPurchaseOrder = document.getElementById('form-purchase-order');

    if (!supplierSelect || !productSelect || !poItemsBody) return;

    const allProductOptions = Array.from(productSelect.options).slice(1);

    supplierSelect.addEventListener('change', function () {
        const selectedSupplierId = this.value;

        poItemsBody.innerHTML = '';
        updateTotal();

        if (productSearchInput) productSearchInput.value = '';
        productSelect.innerHTML = '<option value="">-- Selecciona un producto --</option>';

        if (!selectedSupplierId) {
            productSelect.disabled = true;
            if (productSearchInput) productSearchInput.disabled = true;
            return;
        }

        const filteredOptions = allProductOptions.filter(option => {
            return String(option.getAttribute('data-supplier-id')) === String(selectedSupplierId);
        });

        if (filteredOptions.length > 0) {
            filteredOptions.forEach(option => productSelect.appendChild(option.cloneNode(true)));
            productSelect.disabled = false;
            if (productSearchInput) productSearchInput.disabled = false;
        } else {
            productSelect.innerHTML = '<option value="">-- Este proveedor no tiene productos asignados --</option>';
            productSelect.disabled = true;
            if (productSearchInput) productSearchInput.disabled = true;
        }
    });

    if (productSearchInput) {
        productSearchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const options = Array.from(productSelect.options);

            options.forEach((option, index) => {
                if (index === 0) return; // Omitir placeholder
                const text = option.textContent.toLowerCase();
                option.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    if (btnAddProduct) {
        btnAddProduct.addEventListener('click', function () {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productId = productSelect.value;
            const rawQty = parseInt(quantityInput.value) || 1;
            const quantity = Math.max(1, rawQty);

            if (!productId) {
                alert('Por favor, selecciona un producto.');
                return;
            }

            const name = selectedOption.getAttribute('data-name') || selectedOption.textContent;
            const cost = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
            const subtotal = cost * quantity;

            if (poItemsBody.querySelector(`tr[data-product-id="${productId}"]`)) {
                alert('El producto ya está agregado en la orden.');
                return;
            }

            const row = document.createElement('tr');
            row.setAttribute('data-product-id', productId);
            row.style.borderBottom = '1px solid #eee';

            row.innerHTML = `
                <td style="padding: 8px 0;">
                    ${name}
                    <input type="hidden" name="products[${productId}][id]" value="${productId}">
                    <input type="hidden" name="products[${productId}][name]" value="${name}">
                    <input type="hidden" name="products[${productId}][cost]" value="${cost}">
                </td>
                <td style="padding: 8px 0; text-align: center;">
                    <input type="number" name="products[${productId}][quantity]" value="${quantity}" min="1" class="item-qty" style="width: 60px; text-align: center;">
                </td>
                <td style="padding: 8px 0; text-align: right;">$${cost.toFixed(2)}</td>
                <td style="padding: 8px 0; text-align: right;" class="item-subtotal">$${subtotal.toFixed(2)}</td>
                <td style="padding: 8px 0; text-align: center;">
                    <button type="button" class="btn-cancel btn-remove-item" style="padding: 2px 6px; font-size: 12px;">X</button>
                </td>
            `;

            poItemsBody.appendChild(row);
            updateTotal();

            productSelect.value = '';
            if (productSearchInput) productSearchInput.value = '';
            quantityInput.value = 1;
        });
    }

    poItemsBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('item-qty')) {
            const row = e.target.closest('tr');
            const costInput = row.querySelector(`input[name*="[cost]"]`);
            const cost = parseFloat(costInput ? costInput.value : 0) || 0;
            const qty = Math.max(1, parseInt(e.target.value) || 1);
            
            e.target.value = qty;
            const subtotal = cost * qty;

            const subtotalCell = row.querySelector('.item-subtotal');
            if (subtotalCell) subtotalCell.textContent = `$${subtotal.toFixed(2)}`;
            
            updateTotal();
        }
    });

    poItemsBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('tr').remove();
            updateTotal();
        }
    });

    function updateTotal() {
        let total = 0;
        const rows = poItemsBody.querySelectorAll('tr');

        rows.forEach(row => {
            const costInput = row.querySelector(`input[name*="[cost]"]`);
            const qtyInput = row.querySelector('.item-qty');
            if (costInput && qtyInput) {
                total += (parseFloat(costInput.value) || 0) * (parseInt(qtyInput.value) || 0);
            }
        });

        if (poTotalSpan) {
            poTotalSpan.textContent = total.toFixed(2);
        }
    }

    if (formPurchaseOrder) {
        formPurchaseOrder.addEventListener('submit', function (e) {
            if (poItemsBody.children.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un producto a la orden de compra.');
            }
        });
    }
}
