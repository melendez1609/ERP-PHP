export function initBarcodes() {
    const productSelects = document.querySelectorAll('.product-select');

    productSelects.forEach(productSelect => {
        productSelect.addEventListener('change', function() {
            updateBatchOptions(this);
        });
    });

    const generateForm = document.getElementById('form-generate-barcodes') || document.querySelector('form[action*="barcodes.generate"]');

    if (generateForm) {
        generateForm.addEventListener('submit', function(e) {
            const productSelect = this.querySelector('.product-select');
            const batchSelect = this.querySelector('.batch-select');

            if (!productSelect || !batchSelect) return;

            const selectedProductCode = productSelect.value;
            const selectedBatchId = batchSelect.value;

            if (!selectedProductCode || !selectedBatchId) return;

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            let hasPdf = false;
            try {
                const rawBatches = selectedOption.getAttribute('data-batches') || selectedOption.dataset.batches || '[]';
                const batches = JSON.parse(rawBatches);
                const currentBatch = batches.find(batch => String(batch.id) === String(selectedBatchId));
                hasPdf = Boolean(currentBatch && currentBatch.has_pdf);
            } catch (err) {
                console.error('Error al verificar PDF:', err);
            }

            if (hasPdf) {
                e.preventDefault();

                const alertModal = document.getElementById('modal-alert');
                if (!alertModal) return;

                const titleEl = alertModal.querySelector('.modal-title');
                const messageEl = alertModal.querySelector('.modal-message');
                const confirmBtn = alertModal.querySelector('.btn-alert-confirm');
                const alertForm = alertModal.querySelector('form');

                if (alertForm) {
                    alertForm.onsubmit = function(evt) {
                        evt.preventDefault();
                    };
                }

                if (titleEl) titleEl.textContent = 'Sobrescribir PDF';
                if (messageEl) messageEl.textContent = `El lote #${selectedBatchId} ya cuenta con un PDF generado. ¿Deseas rehacerlo?`;

                if (confirmBtn) {
                    confirmBtn.textContent = 'Rehacer PDF';

                    const newConfirmBtn = confirmBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

                    newConfirmBtn.addEventListener('click', function(evt) {
                        evt.preventDefault();
                        evt.stopPropagation();

                        closeCustomModal(alertModal);
                        updateMemoryAndSearch(selectedProductCode, selectedBatchId);
                        generateForm.submit();
                    });
                }

                const closeBtns = alertModal.querySelectorAll('[data-modal-close]');
                closeBtns.forEach(btn => {
                    btn.onclick = function(evt) {
                        evt.preventDefault();
                        closeCustomModal(alertModal);
                    };
                });

                openCustomModal(alertModal);
            } else {
                updateMemoryAndSearch(selectedProductCode, selectedBatchId);
            }
        });
    }
}

function openCustomModal(modal) {
    modal.classList.add('active', 'show');
    modal.style.display = 'flex';
    modal.style.zIndex = '9999';
}

function closeCustomModal(modal) {
    modal.classList.remove('active', 'show');
    modal.style.display = 'none';
    modal.style.zIndex = '';
}

function updateMemoryAndSearch(selectedProductCode, selectedBatchId) {
    document.querySelectorAll('.product-select option').forEach(option => {
        if (option.value === selectedProductCode) {
            try {
                const rawBatches = option.getAttribute('data-batches') || option.dataset.batches || '[]';
                let batches = JSON.parse(rawBatches);

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