export function initEditInventory() {
    const editButtons = document.querySelectorAll('.inventory-table-button.edit');
    const scopeRadios = document.querySelectorAll('input[name="price_update_scope"]');
    const batchContainer = document.getElementById('batch-selection-container');
    const batchSelect = document.getElementById('edit_batch_id');
    const pricingStockContainer = document.getElementById('pricing-fields-container');

    const costInput = document.getElementById('edit_cost');
    const profitInput = document.getElementById('edit_profit_percentage');
    const priceInput = document.getElementById('edit_price');
    const stockInput = document.getElementById('edit_stock');
    const stockContainer = document.getElementById('edit-stock-container');
    const imageInput = document.getElementById('edit_image');

    if (!editButtons.length) return;

    let currentBatches = [];
    let initialProductData = {};

    function togglePricingStockSection(scope) {
        if (!pricingStockContainer) return;

        const priceVatInputs = pricingStockContainer.querySelectorAll('#edit_cost, #edit_vat_id, #edit_profit_percentage, #edit_price');

        if (scope === 'none') {
            pricingStockContainer.style.display = 'none';
            priceVatInputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
            if (stockInput) {
                stockInput.disabled = true;
                stockInput.removeAttribute('required');
            }
        } else if (scope === 'all_batches') {
            pricingStockContainer.style.display = 'block';
            priceVatInputs.forEach(input => {
                input.disabled = false;
                input.setAttribute('required', 'true');
            });

            if (stockInput) {
                stockInput.disabled = true;
                stockInput.removeAttribute('required');
            }
            if (stockContainer) stockContainer.style.display = 'none';

        } else if (scope === 'specific_batch') {
            pricingStockContainer.style.display = 'block';
            priceVatInputs.forEach(input => {
                input.disabled = false;
                input.setAttribute('required', 'true');
            });

            if (stockInput) {
                stockInput.disabled = false;
                stockInput.setAttribute('required', 'true');
            }
            if (stockContainer) stockContainer.style.display = 'block';
        }
    }

    scopeRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            const scope = e.target.value;
            const isSpecific = scope === 'specific_batch';
            
            if (batchContainer) {
                batchContainer.style.display = isSpecific ? 'block' : 'none';
            }

            togglePricingStockSection(scope);

            if (scope === 'all_batches') {
                if (costInput) costInput.value = initialProductData.cost || '';
                if (profitInput) profitInput.value = initialProductData.profitPercentage || '';
                if (priceInput) priceInput.value = initialProductData.price || '';
            } else if (isSpecific && batchSelect && batchSelect.value) {
                batchSelect.dispatchEvent(new Event('change'));
            }
        });
    });

    if (batchSelect) {
        batchSelect.addEventListener('change', (e) => {
            const batchId = e.target.value;
            const selectedBatch = currentBatches.find(b => b.id == batchId);

            if (selectedBatch) {
                if (costInput) costInput.value = parseFloat(selectedBatch.cost).toFixed(2);
                if (profitInput) profitInput.value = parseFloat(selectedBatch.margin_percentage).toFixed(2);
                if (priceInput) priceInput.value = parseFloat(selectedBatch.price).toFixed(2);
                if (stockInput) stockInput.value = selectedBatch.quantity_remaining;
            }
        });
    }

    editButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const form = document.getElementById('edit-inventory-form');
            if (!form) return;

            const id = button.dataset.id;
            form.action = `/inventory/${id}`;

            if (imageInput) imageInput.value = '';

            initialProductData = {
                cost: button.dataset.cost || '',
                profitPercentage: button.dataset.profitPercentage || '',
                price: button.dataset.price || '',
                stock: button.dataset.stock || ''
            };

            const inputCode = document.getElementById('edit_code');
            const inputName = document.getElementById('edit_name');
            const inputDescription = document.getElementById('edit_description');
            const inputMinStock = document.getElementById('edit_min_stock');
            const vatSelect = document.getElementById('edit_vat_id');
            const profitInputElem = document.getElementById('edit_profit_percentage');
            const supplierSelect = document.getElementById('edit_supplier_id');
            const statusSelect = document.getElementById('edit_product_status_id');

            if (inputCode) inputCode.value = button.dataset.code || '';
            if (inputName) inputName.value = button.dataset.name || '';
            if (inputDescription) inputDescription.value = button.dataset.description || '';
            if (inputMinStock) inputMinStock.value = button.dataset.minStock || '';
            if (vatSelect) vatSelect.value = button.dataset.vatId || '';
            if (profitInputElem) profitInputElem.value = button.dataset.profitPercentage || '';
            if (supplierSelect) supplierSelect.value = button.dataset.supplierId || '';
            if (statusSelect) statusSelect.value = button.dataset.statusId || '';

            const defaultScope = document.querySelector('input[name="price_update_scope"][value="none"]');
            if (defaultScope) defaultScope.checked = true;
            if (batchContainer) batchContainer.style.display = 'none';

            togglePricingStockSection('none');

            if (batchSelect) {
                batchSelect.innerHTML = '<option value="">Cargando lotes...</option>';
                try {
                    const response = await fetch(`/inventory/${id}/batches`);
                    currentBatches = await response.json();

                    batchSelect.innerHTML = '<option value="">-- Seleccionar Lote --</option>';
                    currentBatches.forEach(batch => {
                        const option = document.createElement('option');
                        option.value = batch.id;
                        option.textContent = `Lote #${batch.id} - Stock: ${batch.quantity_remaining} - Precio actual: $${parseFloat(batch.price).toFixed(2)}`;
                        batchSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error al cargar lotes:', error);
                    batchSelect.innerHTML = '<option value="">Error al cargar lotes</option>';
                }
            }
        });
    });
}