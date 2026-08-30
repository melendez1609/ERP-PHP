<div id="modal-volume" class="modal" style="z-index: 9999;">
    <div class="modal-content" style="max-width: 420px; width: 90%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 1.15rem;">Ajustar Volumen</h3>
            <button type="button" class="modal-close" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 5px 0;">
            <div class="form-group" style="display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="volume-range" style="font-size: 0.95rem; font-weight: 600; color: #374151;">Nivel de Sonido</label>
                    <span id="volume-percentage" style="font-size: 0.95rem; font-weight: 700; color: #2563eb;">100%</span>
                </div>
                <input type="range" 
                       id="volume-range" 
                       min="0" 
                       max="100" 
                       value="100" 
                       style="width: 100%; display: block; box-sizing: border-box; margin: 4px 0 0 0; padding: 0; cursor: pointer; accent-color: #2563eb;">
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close>Listo</button>
        </div>
    </div>
</div>