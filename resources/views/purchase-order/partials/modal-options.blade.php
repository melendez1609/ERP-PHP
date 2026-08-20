<div id="modal-purchase-options" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal-close>&times;</span>
        <h3>Opciones de Órdenes de Compra</h3>

        <div class="modal-body">
            <div class="modal-actions" style="justify-content: center; gap: 15px; margin-top: 20px;">
                <button type="button" 
                        class="btn-save" 
                        data-modal-target="modal-purchase-order" 
                        data-modal-close>
                    Orden de Compra
                </button>

                <a href="{{ route('purchase-orders.index') }}" class="btn-cancel" style="text-decoration: none; display: inline-block; line-height: normal; text-align: center;">
                    Rastreo de Órdenes
                </a>
            </div>
        </div>
    </div>
</div>