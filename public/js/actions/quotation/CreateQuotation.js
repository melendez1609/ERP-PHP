export function initCreateQuotation() {
    const btnAdd = document.getElementById('btn-add-product');
    const itemsBody = document.getElementById('quotation-items-body');
    const totalDisplay = document.getElementById('quotation-total');
    const formQuotation = document.getElementById('form-quotation');
    const modalQuotations = document.getElementById('modal-quotations');

    let itemIndex = 0;

    if (!btnAdd || !itemsBody || !totalDisplay) return;

    function calculateTotal() {
        let total = 0;
        const subtotals = itemsBody.querySelectorAll('.item-subtotal-val');
        subtotals.forEach(item => {
            total += parseFloat(item.value) || 0;
        });
        totalDisplay.textContent = total.toFixed(2);
    }

    btnAdd.addEventListener('click', function() {
        const select = document.getElementById('select-product');
        const quantityInput = document.getElementById('input-quantity');
        
        const productId = select.value;
        const selectedOption = select.options[select.selectedIndex];
        
        if (!productId) {
            alert('Selecciona un producto');
            return;
        }

        const productName = selectedOption.getAttribute('data-name');
        const productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = parseInt(quantityInput.value) || 1;
        const subtotal = productPrice * quantity;

        const row = document.createElement('tr');
        row.style.borderBottom = '1px solid #eee';
        row.innerHTML = `
            <td style="padding: 8px 0;">
                ${productName}
                <input type="hidden" name="products[${itemIndex}][id]" value="${productId}">
            </td>
            <td style="text-align: center; padding: 8px 0;">
                ${quantity}
                <input type="hidden" name="products[${itemIndex}][quantity]" value="${quantity}">
            </td>
            <td style="text-align: right; padding: 8px 0;">$${productPrice.toFixed(2)}</td>
            <td style="text-align: right; padding: 8px 0;">
                $${subtotal.toFixed(2)}
                <input type="hidden" class="item-subtotal-val" value="${subtotal}">
            </td>
            <td style="text-align: center; padding: 8px 0;">
                <button type="button" class="btn-cancel btn-remove-item" style="padding: 2px 8px; cursor: pointer;">&times;</button>
            </td>
        `;

        itemsBody.appendChild(row);
        itemIndex++;
        calculateTotal();

        select.value = '';
        quantityInput.value = 1;
    });

    itemsBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('tr').remove();
            calculateTotal();
        }
    });

    if (formQuotation) {
        formQuotation.addEventListener('submit', function() {
            setTimeout(() => {
                formQuotation.reset();

                itemsBody.innerHTML = '';
                itemIndex = 0;
                calculateTotal();

                if (modalQuotations) {
                    const closeBtn = modalQuotations.querySelector('[data-modal-close]');
                    if (closeBtn) {
                        closeBtn.click();
                    } else {
                        modalQuotations.style.display = 'none';
                    }
                }
            }, 100);
        });
    }
}