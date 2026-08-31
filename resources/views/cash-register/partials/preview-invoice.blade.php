<div id="modal-ticket-preview" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: #fff; padding: 20px; border-radius: 8px; width: 360px; max-height: 90vh; display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
            <h4 style="margin: 0; color: #132873;">Previsualización de Ticket</h4>
            <button type="button" id="close-preview-modal" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>
        
        <div style="flex-grow: 1; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; background: #f8fafc;">
            <iframe id="preview-ticket-iframe" style="width: 100%; height: 380px; border: none;"></iframe>
        </div>

        <button type="button" id="btn-print-from-preview" class="inventory-table-button edit" style="width: 100%; padding: 8px;">Imprimir Proforma</button>
    </div>
</div>