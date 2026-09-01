<div id="modal-search-invoice" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; width: 450px; max-height: 90vh; display: flex; flex-direction: column; gap: 15px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
            <h3 style="margin: 0; color: #132873;">Buscar Facturas / Tickets</h3>
            <button type="button" class="close btn-cancel" data-modal-close style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        <form id="form-search-invoice" style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <label style="font-size: 0.85rem; font-weight: bold; color: #334155;">Número de Ticket</label>
                <input type="text" id="search-ticket-number" placeholder="Ej. 1 o 00000001" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1; display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 0.85rem; font-weight: bold; color: #334155;">Fecha</label>
                    <input type="date" id="search-ticket-date" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 0.85rem; font-weight: bold; color: #334155;">Hora (Opcional)</label>
                    <input type="time" id="search-ticket-time" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                </div>
            </div>

            <button type="submit" class="btn btn-save" style="padding: 10px; background-color: #132873; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 5px;">Buscar Ticket</button>
        </form>

        <div id="search-results-container" style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 4px; padding: 5px; display: none;">
        </div>

        <div style="display: flex; justify-content: flex-end; border-top: 1px solid #ddd; padding-top: 8px;">
            <button type="button" class="btn btn-cancel" data-modal-close style="padding: 6px 12px; cursor: pointer;">Cerrar</button>
        </div>
    </div>
</div>