export function initEditInventory() {
    const editButtons = document.querySelectorAll('.inventory-table-button.edit');

    if (!editButtons.length) return;

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const form = document.getElementById('form-edit');
            if (!form) return;

            const id = button.dataset.id;
            form.action = `/inventory/${id}`;

            const fields = ['code', 'name', 'description', 'cost', 'price', 'stock'];
            fields.forEach(field => {
                const input = document.getElementById(`edit-${field}`);
                if (input) input.value = button.dataset[field] || '';
            });

            const minStockInput = document.getElementById('edit-min-stock');
            if (minStockInput) minStockInput.value = button.dataset.minStock || '';

            // Asignación de IVA y Porcentaje de Ganancia
            const vatSelect = document.getElementById('edit-vat-id');
            if (vatSelect) vatSelect.value = button.dataset.vatId || '';

            const profitInput = document.getElementById('edit-profit-percentage');
            if (profitInput) profitInput.value = button.dataset.profitPercentage || '';

            const supplierSelect = document.getElementById('edit-supplier-id');
            if (supplierSelect) supplierSelect.value = button.dataset.supplierId || '';

            const statusSelect = document.getElementById('edit-status-id');
            if (statusSelect) statusSelect.value = button.dataset.statusId || '';
        });
    });
}