<div id="modal-alert" class="modal"
     data-auto-open="{{ (session('cash_discrepancy_warning') || session('cash_closed_message') || session('success') || session('error') || $errors->any()) ? 'true' : 'false' }}"
     data-is-discrepancy="{{ session('cash_discrepancy_warning') ? 'true' : 'false' }}"
     data-title="{{ session('cash_discrepancy_warning') ? session('discrepancy_title') : (session('cash_closed_title') ?? (session('success') ? 'Operación Exitosa' : (session('error') ? 'Atención' : ($errors->any() ? 'Error de Validación' : '')))) }}"
     data-message="{{ session('cash_discrepancy_warning') ? session('discrepancy_message') : (session('cash_closed_message') ?? session('success') ?? session('error') ?? $errors->first()) }}">
    <div class="modal-content">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 class="modal-title" style="margin: 0; font-size: 1.25rem; color: #132873;">Confirmar Acción</h3>
            <span class="close" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1;">&times;</span>
        </div>
        <form method="POST" action="{{ session('cash_discrepancy_warning') ? route('cash-register.close') : '' }}">
            @csrf
            @if(session('cash_discrepancy_warning'))
                <input type="hidden" name="closing_amount" value="{{ session('closing_amount') }}">
                <input type="hidden" name="admin_password" value="{{ session('admin_password') }}">
                <input type="hidden" name="confirm_discrepancy" value="1">
            @endif
            <div class="modal-body" style="margin-bottom: 15px;">
                <p class="modal-message" style="margin: 0; font-size: 0.95rem; color: #374151;">¿Estás seguro de realizar esta acción?</p>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                <button type="button" class="btn btn-cancel" data-modal-close style="padding: 6px 14px; border-radius: 6px; border: none; cursor: pointer; background: #e5e7eb; color: #374151;">Cancelar</button>
                <button type="submit" class="btn btn-alert-confirm" style="padding: 6px 14px; border-radius: 6px; border: none; cursor: pointer; background: #d84130; color: white; display: {{ session('cash_discrepancy_warning') ? 'inline-block' : 'none' }};">
                    {{ session('cash_discrepancy_warning') ? 'Confirmar Cierre' : 'Confirmar' }}
                </button>
            </div>
        </form>
    </div>
</div>