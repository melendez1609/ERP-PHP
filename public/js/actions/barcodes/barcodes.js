export function initBarcodes() {
    const productSelects = document.querySelectorAll('.product-select');

    // Escuchar el cambio de producto en cualquiera de los formularios
    productSelects.forEach(productSelect => {
        productSelect.addEventListener('change', function() {
            updateBatchOptions(this);
        });
    });

    const generateForm = document.getElementById('form-generate-barcodes') || document.querySelector('form[action*="barcodes.generate"]');

    if (generateForm) {
        generateForm.addEventListener('submit', function() {
            const productSelect = this.querySelector('.product-select');
            const batchSelect = this.querySelector('.batch-select');

            if (!productSelect || !batchSelect) return;

            const selectedProductCode = productSelect.value;
            const selectedBatchId = batchSelect.value;

            if (!selectedProductCode || !selectedBatchId) return;

            document.querySelectorAll('.product-select option').forEach(option => {
                if (option.value === selectedProductCode) {
                    try {
                        const rawBatches = option.getAttribute('data-batches') || option.dataset.batches || '[]';
                        let batches = JSON.parse(rawBatches);

                        // Marcar el lote recién generado como con PDF (has_pdf: true)
                        batches = batches.map(batch => {
                            if (String(batch.id) === String(selectedBatchId)) {
                                return { ...batch, has_pdf: true };
                            }
                            return batch;
                        });

                        const updatedJson = JSON.stringify(batches);
                        option.setAttribute('data-batches', updatedJson);
                        option.dataset.batches = updatedJson;
                    } catch (e) {
                        console.error('Error actualizando lote generado:', e);
                    }
                }
            });

            const searchProductSelect = document.querySelector('.product-select[data-filter-pdf="true"]');
            if (searchProductSelect) {
                updateBatchOptions(searchProductSelect);
            }
        });
    }
}

function updateBatchOptions(productSelect) {
    if (!productSelect) return;

    const form = productSelect.closest('form');
    if (!form) return;

    const batchSelect = form.querySelector('.batch-select');
    if (!batchSelect) return;

    const selectedOption = productSelect.options[productSelect.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
        batchSelect.innerHTML = '<option value="" disabled selected>Seleccionar Lote</option>';
        batchSelect.disabled = true;
        return;
    }

    const filterPdfOnly = productSelect.dataset.filterPdf === 'true';

    let batches = [];
    try {
        const rawBatches = selectedOption.getAttribute('data-batches') || selectedOption.dataset.batches || '[]';
        batches = JSON.parse(rawBatches);
    } catch (e) {
        console.error('Error al procesar lotes:', e);
    }

    if (filterPdfOnly) {
        batches = batches.filter(batch => Boolean(batch.has_pdf));
    }

    batchSelect.innerHTML = '<option value="" disabled selected>Seleccionar Lote</option>';

    if (batches.length > 0) {
        batches.forEach(batch => {
            const option = document.createElement('option');
            option.value = batch.id;
            const stock = batch.quantity_remaining ?? batch.quantity_received ?? 0;
            option.textContent = `Lote #${batch.id} (${stock} unid.)`;
            batchSelect.appendChild(option);
        });
        batchSelect.disabled = false;
    } else {
        const option = document.createElement('option');
        option.value = "";
        option.textContent = filterPdfOnly ? "Sin PDFs generados" : "Sin lotes disponibles";
        batchSelect.appendChild(option);
        batchSelect.disabled = true;
    }
}