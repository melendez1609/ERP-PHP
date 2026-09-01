export function initInvoiceSearch() {
    const searchModal = document.getElementById('modal-search-invoice');
    const previewModal = document.getElementById('modal-ticket-preview');
    const searchForm = document.getElementById('form-search-invoice');
    const resultsContainer = document.getElementById('search-results-container');
    const iframePreview = document.getElementById('preview-ticket-iframe');
    const printBtn = document.getElementById('btn-print-from-preview');

    if (!searchModal) return;

    document.querySelectorAll('[data-modal-target="modal-search-invoice"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            searchModal.style.display = 'flex';
            searchModal.classList.add('active', 'show');
            if (resultsContainer) {
                resultsContainer.style.display = 'none';
                resultsContainer.innerHTML = '';
            }
            if (searchForm) searchForm.reset();
        });
    });

    document.querySelectorAll('#modal-search-invoice [data-modal-close], #modal-search-invoice .close').forEach(btn => {
        btn.addEventListener('click', () => {
            searchModal.style.display = 'none';
            searchModal.classList.remove('active', 'show');
        });
    });

    previewModal?.querySelector('#close-preview-modal')?.addEventListener('click', () => {
        previewModal.style.display = 'none';
    });

    printBtn?.addEventListener('click', () => {
        if (iframePreview && iframePreview.contentWindow) {
            iframePreview.contentWindow.focus();
            iframePreview.contentWindow.print();
        }
    });

    searchForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const ticketNumber = document.getElementById('search-ticket-number').value.trim();
        const date = document.getElementById('search-ticket-date').value;
        const time = document.getElementById('search-ticket-time').value;

        const params = new URLSearchParams();
        if (ticketNumber) params.append('ticket_number', ticketNumber);
        if (date) params.append('date', date);
        if (time) params.append('time', time);

        try {
            resultsContainer.style.display = 'block';
            resultsContainer.innerHTML = '<div style="padding: 10px; text-align: center; color: #64748b;">Buscando tickets...</div>';

            const response = await fetch(`/sales/search?${params.toString()}`);
            const data = await response.json();

            if (data.success && data.sales.length > 0) {
                resultsContainer.innerHTML = '';
                data.sales.forEach(sale => {
                    const row = document.createElement('div');
                    row.style.cssText = 'padding: 8px 12px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-size: 0.9rem; background: #fff; transition: background 0.2s;';
                    row.onmouseover = () => row.style.background = '#f8fafc';
                    row.onmouseout = () => row.style.background = '#fff';

                    row.innerHTML = `
                        <div>
                            <strong>Ticket #${String(sale.id).padStart(8, '0')}</strong><br>
                            <span style="font-size: 0.8rem; color: #64748b;">${sale.created_at} - Total: $${Number(sale.total).toFixed(2)}</span>
                        </div>
                        <button type="button" class="btn btn-save" style="padding: 4px 8px; font-size: 0.8rem; background-color: #132873; color: white; border: none; border-radius: 4px; cursor: pointer;">Ver / Imprimir</button>
                    `;

                    row.addEventListener('click', () => {
                        searchModal.style.display = 'none';
                        previewModal.style.display = 'flex';
                        if (iframePreview) {
                            iframePreview.src = `/cash-register/ticket/${sale.id}`;
                        }
                    });

                    resultsContainer.appendChild(row);
                });
            } else {
                resultsContainer.innerHTML = '<div style="padding: 10px; text-align: center; color: #dc2626;">No se encontraron tickets con esos criterios.</div>';
            }
        } catch (err) {
            console.error(err);
            resultsContainer.innerHTML = '<div style="padding: 10px; text-align: center; color: #dc2626;">Ocurrió un error al realizar la búsqueda.</div>';
        }
    });
}