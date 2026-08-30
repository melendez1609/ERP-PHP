<div id="modal-view-schedule" class="modal">
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Detalles de la Actividad</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">
            <div>
                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Título</span>
                <p id="view_event_title" style="font-size: 15px; font-weight: 600; color: #0f172a; margin-top: 2px;"></p>
            </div>

            <div style="display: flex; gap: 30px;">
                <div>
                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Fecha</span>
                    <p id="view_event_date" style="font-size: 13px; color: #0f172a; margin-top: 2px;"></p>
                </div>
                <div>
                    <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Hora</span>
                    <p id="view_event_time" style="font-size: 13px; color: #0f172a; margin-top: 2px;"></p>
                </div>
            </div>

            <div>
                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Descripción</span>
                <p id="view_event_description" style="font-size: 13px; color: #0f172a; margin-top: 2px; white-space: pre-wrap; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;"></p>
            </div>

            <div id="view_event_creator_wrapper" style="display: none;">
                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Creado por</span>
                <p id="view_event_creator" style="font-size: 13px; color: #3b82f6; font-weight: 600; margin-top: 2px;"></p>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close>Cerrar</button>
        </div>
    </div>
</div>