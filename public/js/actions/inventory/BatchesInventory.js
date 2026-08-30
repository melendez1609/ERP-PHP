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
            const productImage = button.dataset.image;

            if (modalTitle) modalTitle.textContent = `Lotes: ${productCode} - ${productName}`;
            if (tableBody) tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;">Cargando lotes...</td></tr>';

            try {
                const response = await fetch(`/inventory/${productId}/batches`);
                if (!response.ok) throw new Error('Error al obtener los lotes');

                const batches = await response.json();

                if (batches.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;">No hay lotes con stock disponible.</td></tr>';
                    return;
                }

                tableBody.innerHTML = batches.map(batch => {
                    let imageHtml = '<span style="color: #9ca3af; font-size: 1.5vh;">Sin foto</span>';
                    
                    if (productImage && productImage !== '') {
                        const imageUrl = `/storage/${productImage}`;
                        imageHtml = `<a href="${imageUrl}" target="_blank" title="Ver fotografía del producto" style="display: inline-flex; align-items: center; justify-content: center;">
                                        <img src="/icons/picture.png" alt="Ver foto" class="product-image">
                                    </a>`;
                    }

                    const deleteUrl = `/inventory/batches/${batch.id}`;

                    return `
                        <tr>
                            <td style="text-align: center;">${imageHtml}</td>
                            <td>#${batch.id}</td>
                            <td>${batch.quantity_received}</td>
                            <td><strong>${batch.quantity_remaining}</strong></td>
                            <td>$${parseFloat(batch.cost).toFixed(2)}</td>
                            <td>${batch.margin_percentage}%</td>
                            <td>$${parseFloat(batch.price).toFixed(2)}</td>
                            <td>${new Date(batch.created_at).toLocaleDateString()}</td>
                            <td style="text-align: center;">
                                <button type="button" 
                                        class="btn-danger" 
                                        style="padding: 6px 14px; font-size: 13px; cursor: pointer; border-radius: 4px;"
                                        data-modal-target="modal-alert"
                                        data-action="${deleteUrl}"
                                        data-method="DELETE"
                                        data-title="Eliminar Lote"
                                        data-message="¿Estás seguro de eliminar el Lote #${batch.id}? Esto descontará ${batch.quantity_remaining} unidades del stock actual del producto."
                                        data-btn-text="Eliminar Lote"
                                        data-btn-class="btn-danger"
                                        onclick="document.getElementById('modal-batches').style.display='none';">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');

            } catch (error) {
                console.error(error);
                tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; color:red;">Error al cargar la información.</td></tr>';
            }
        });
    });
}