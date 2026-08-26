export function initBatchesInventory() {
    const batchButtons = document.querySelectorAll('.inventory-table-button.view-batches');
    const tableBody = document.getElementById('batches-table-body');
    const modalTitle = document.getElementById('batches-modal-title');

    if (!batchButtons.length) return;

    batchButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const productId = button.dataset.id;
            const productName = button.dataset.name;
            const productCode = button.dataset.code;

            if (modalTitle) modalTitle.textContent = `Lotes: ${productCode} - ${productName}`;
            if (tableBody) tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Cargando lotes...</td></tr>';

            try {
                const response = await fetch(`/inventory/${productId}/batches`);
                if (!response.ok) throw new Error('Error al obtener los lotes');

                const batches = await response.json();

                if (batches.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No hay lotes con stock disponible.</td></tr>';
                    return;
                }

                tableBody.innerHTML = batches.map(batch => `
                    <tr>
                        <td>#${batch.id}</td>
                        <td>${batch.quantity_received}</td>
                        <td><strong>${batch.quantity_remaining}</strong></td>
                        <td>$${parseFloat(batch.cost).toFixed(2)}</td>
                        <td>${batch.margin_percentage}%</td>
                        <td>$${parseFloat(batch.price).toFixed(2)}</td>
                        <td>${new Date(batch.created_at).toLocaleDateString()}</td>
                    </tr>
                `).join('');

            } catch (error) {
                console.error(error);
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:red;">Error al cargar la información.</td></tr>';
            }
        });
    });
}