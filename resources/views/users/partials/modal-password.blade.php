<div id="modal-password" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Cambiar Contraseña</h3>
            <button type="button" class="modal-close" data-modal-close>&times;</button>
        </div>
        
        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="form-control" 
                           required 
                           autocomplete="current-password">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           required 
                           autocomplete="new-password">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control" 
                           required 
                           autocomplete="new-password">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-modal-close>Cancelar</button>
                <button type="submit" class="btn btn-save">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>