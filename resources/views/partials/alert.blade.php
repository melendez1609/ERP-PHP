<div id="modal-alert" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirmar acción</h3>
            <button type="button" class="modal-close" data-modal-close>&times;</button>
        </div>
        <form method="POST" action="">
            @csrf
            <div class="modal-body">
                <p class="modal-message">¿Estás seguro de realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-alert-confirm">Confirmar</button>
            </div>
        </form>
    </div>
</div>