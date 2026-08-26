export function initEditInventory() {
    const editButtons = document.querySelectorAll('.inventory-table-button.edit');
    const scopeRadios = document.querySelectorAll('input[name="price_update_scope"]');
    const batchContainer = document.getElementById('specific-batch-container');
    const batchSelect = document.getElementById('edit-batch-id');
    const pricingStockContainer = document.getElementById('pricing-stock-container');

    const costInput = document.getElementById('edit-cost');
    const profitInput = document.getElementById('edit-profit-percentage');
    const priceInput = document.getElementById('edit-price');
    const stockInput = document.getElementById('edit-stock');
    const stockGroup = document.getElementById('edit-stock-group');

    if (!editButtons.length) return;

    let currentBatches = [];
    let initialProductData = {};

    function togglePricingStockSection(scope) {
        if (!pricingStockContainer) return;

        const priceVatInputs = pricingStockContainer.querySelectorAll('#edit-cost, #edit-vat-id, #edit-profit-percentage, #edit-price');

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
            if (stockGroup) stockGroup.style.display = 'none';

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
            if (stockGroup) stockGroup.style.display = 'block';
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
            const form = document.getElementById('form-edit');
            if (!form) return;

            const id = button.dataset.id;
            form.action = `/inventory/${id}`;

            const hiddenId = document.getElementById('edit-id');
            if (hiddenId) hiddenId.value = id;

            initialProductData = {
                cost: button.dataset.cost || '',
                profitPercentage: button.dataset.profitPercentage || '',
                price: button.dataset.price || '',
                stock: button.dataset.stock || ''
            };

            const fields = ['code', 'name', 'description', 'cost', 'price', 'stock'];
            fields.forEach(field => {
                const input = document.getElementById(`edit-${field}`);
                if (input) input.value = button.dataset[field] || '';
            });

            const minStockInput = document.getElementById('edit-min-stock');
            if (minStockInput) minStockInput.value = button.dataset.minStock || '';

            const vatSelect = document.getElementById('edit-vat-id');
            if (vatSelect) vatSelect.value = button.dataset.vatId || '';

            const profitInput = document.getElementById('edit-profit-percentage');
            if (profitInput) profitInput.value = button.dataset.profitPercentage || '';

            const supplierSelect = document.getElementById('edit-supplier-id');
            if (supplierSelect) supplierSelect.value = button.dataset.supplierId || '';

            const statusSelect = document.getElementById('edit-status-id');
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