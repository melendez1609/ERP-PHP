<div id="modal-options" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Opciones de Cotización</h3>

        <div class="modal-body">
            <div class="modal-actions" style="justify-content: center; gap: 15px; margin-top: 20px;">
                <!-- Abre el modal de creación de cotización (Paso 2.1) -->
                <button type="button" 
                        class="btn-save" 
                        data-modal-target="modal-quotations" 
                        data-modal-close>
                    Cotización
                </button>

                <!-- Redirige a la vista del historial de cotizaciones (Paso 2.2) -->
                <a href="{{ route('quotations.index') }}" class="btn-cancel" style="text-decoration: none; display: inline-block; line-height: normal;">
                    Historial de Cotizaciones
                </a>
            </div>
        </div>
    </div>
</div>