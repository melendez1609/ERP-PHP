<div id="modal-payment" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.25rem; color: #132873;">Procesar Pago</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1;">&times;</span>
        </div>
        <form id="form-process-payment">
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 15px;">
                <div style="background-color: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center;">
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 600; display: block;">TOTAL A PAGAR</span>
                    <span id="pay-modal-total" style="font-size: 1.75rem; font-weight: bold; color: #132873;">$0.00</span>
                </div>

                <div>
                    <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 5px;">Monto Recibido ($)</label>
                    <input type="number" id="pay-modal-received" step="0.01" min="0" placeholder="0.00" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 1.2rem; text-align: right; font-weight: bold;">
                </div>

                <div style="background-color: #f0fdf4; padding: 12px; border-radius: 6px; border: 1px solid #bbf7d0; text-align: center;">
                    <span style="font-size: 0.85rem; color: #166534; font-weight: 600; display: block;">CAMBIO / VUELTO</span>
                    <span id="pay-modal-change" style="font-size: 1.5rem; font-weight: bold; color: #15803d;">$0.00</span>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
                <button type="button" class="btn btn-cancel" data-modal-close style="padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; background: #e5e7eb; color: #374151;">Cancelar</button>
                <button type="submit" id="btn-confirm-payment" class="btn btn-save" style="padding: 8px 16px; background-color: #15803d; color: white; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;" disabled>Confirmar Pago</button>
            </div>
        </form>
    </div>
</div>