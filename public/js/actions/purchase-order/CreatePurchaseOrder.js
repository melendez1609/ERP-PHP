export function initCreatePurchaseOrder() {
    const supplierSelect = document.getElementById('supplier_id');
    const productSelect = document.getElementById('select-product');
    const btnAddProduct = document.getElementById('btn-add-product');
    const itemsBody = document.getElementById('quotation-items-body');
    const totalSpan = document.getElementById('quotation-total');

    if (!supplierSelect || !productSelect) return;

    const allProductOptions = Array.from(productSelect.querySelectorAll('option')).slice(1);

    supplierSelect.addEventListener('change', () => {
        const supplierId = supplierSelect.value;
        itemsBody.innerHTML = ''; 
        updateTotal();

        if (!supplierId) {
            productSelect.disabled = true;
            productSelect.innerHTML = '<option value="">-- Selecciona un proveedor primero --</option>';
            return;
        }

        productSelect.disabled = false;
        
        productSelect.innerHTML = '<option value="">-- Selecciona un producto --</option>';
        allProductOptions.forEach(opt => {
            if (opt.getAttribute('data-supplier-id') === supplierId) {
                productSelect.appendChild(opt.cloneNode(true));
            }
        });
    });

    if (btnAddProduct) {
        btnAddProduct.addEventListener('click', () => {
            const selectedOpt = productSelect.options[productSelect.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) return;

            const productId = selectedOpt.value;
            const productName = selectedOpt.getAttribute('data-name');
            const productCost = parseFloat(selectedOpt.getAttribute('data-price')) || 0;
            const quantityInput = document.getElementById('input-quantity');
            const quantity = parseInt(quantityInput.value) || 1;

            const existingRow = itemsBody.querySelector(`tr[data-product-id="${productId}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.item-quantity');
                qtyInput.value = parseInt(qtyInput.value) + quantity;
                updateRowSubtotal(existingRow);
            } else {
                const row = document.createElement('tr');
                row.setAttribute('data-product-id', productId);
                row.style.borderBottom = '1px solid #eee';
                row.innerHTML = `
                    <td style="padding: 8px 0;">
                        ${productName}
                        <input type="hidden" name="items[${productId}][product_id]" value="${productId}">
                    </td>
                    <td style="padding: 8px 0; text-align: center;">
                        <input type="number" name="items[${productId}][quantity]" value="${quantity}" min="1" class="item-quantity" style="width: 60px; text-align: center;">
                    </td>
                    <td style="padding: 8px 0; text-align: right;">
                        $<input type="hidden" name="items[${productId}][cost]" value="${productCost}">${productCost.toFixed(2)}
                    </td>
                    <td style="padding: 8px 0; text-align: right;" class="item-subtotal">
                        $${(productCost * quantity).toFixed(2)}
                    </td>
                    <td style="padding: 8px 0; text-align: center;">
                        <button type="button" class="btn-cancel btn-remove-item" style="padding: 2px 6px; font-size: 0.9em;">X</button>
                    </td>
                `;
                itemsBody.appendChild(row);

                row.querySelector('.item-quantity').addEventListener('input', () => updateRowSubtotal(row));
                
                row.querySelector('.btn-remove-item').addEventListener('click', () => {
                    row.remove();
                    updateTotal();
                });
            }

            updateTotal();
            if (quantityInput) quantityInput.value = 1;
            productSelect.value = '';
        });
    }

    function updateRowSubtotal(row) {
        const qty = parseInt(row.querySelector('.item-quantity').value) || 0;
        const cost = parseFloat(row.querySelector('input[name*="[cost]"]').value) || 0;
        const subtotal = qty * cost;
        row.querySelector('.item-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        itemsBody.querySelectorAll('tr').forEach(row => {
            const qty = parseInt(row.querySelector('.item-quantity')?.value) || 0;
            const cost = parseFloat(row.querySelector('input[name*="[cost]"]')?.value) || 0;
            total += qty * cost;
        });
        totalSpan.textContent = total.toFixed(2);
    }
}