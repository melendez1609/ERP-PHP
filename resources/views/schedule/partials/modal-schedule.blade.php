<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

.cal06, .cal06 * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.cal06 {
    --surface: #f8fafc;
    --card: #ffffff;
    --ink: #0f172a;
    --muted: #64748b;
    --brand: #1e2b4f;
    --brand-dark: #141c36;
    --brand-light: #3b82f6;
    --accent: #e07a5f;
    --yellow: #f59e0b;
    --purple: #8b5cf6;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink);
    background: transparent;
    min-height: 0;
    display: block;
    padding: 0;
}

.cal06__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 280px;
    gap: 20px;
    width: 100%;
}

.cal06__main {
    background: var(--card);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
    border: 1px solid #e2e8f0;
}

.cal06__top {
    background: var(--brand);
    padding: 20px 24px 16px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.cal06__month-label {
    font-size: 30px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.cal06__year-label {
    font-size: 12px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.7);
    letter-spacing: 0.1em;
    margin-top: 4px;
}

.cal06__top-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
    z-index: 1;
}

.cal06__nav-row {
    display: flex;
    gap: 6px;
}

.cal06__nav-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.cal06__nav-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.cal06__today-pill {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 20px;
    cursor: pointer;
}

.cal06__dow {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 10px 14px 4px;
    background: var(--surface);
}

.cal06__dow-lbl {
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--muted);
}

.cal06__grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 8px 14px 16px;
    gap: 4px;
}

.cal06__cell {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4px 2px;
    border-radius: 10px;
    cursor: pointer;
    min-height: 46px;
    transition: background 0.15s;
}

.cal06__cell:hover { background: var(--surface); }
.cal06__cell.other { opacity: 0.25; pointer-events: none; }

.cal06__cell-n {
    font-size: 14px;
    font-weight: 500;
    color: var(--ink);
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.cal06__cell.today .cal06__cell-n {
    background: var(--brand);
    color: #ffffff;
    font-weight: 700;
}

.cal06__cell.selected .cal06__cell-n {
    background: var(--brand-light);
    color: #ffffff;
    font-weight: 700;
}

.cal06__pips { display: flex; gap: 3px; margin-top: 2px; }
.cal06__pip { width: 5px; height: 5px; border-radius: 50%; }

.cal06__side { display: flex; flex-direction: column; gap: 12px; }
.cal06__side-card {
    background: var(--card);
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #e2e8f0;
}
.cal06__side-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
}

.cal06__date-big {
    font-size: 46px;
    font-weight: 700;
    color: var(--brand-dark);
    line-height: 1;
}

.cal06__dropdown-item:hover {
    background-color: #f1f5f9 !important;
}

.cal06__date-info { font-size: 12px; color: var(--muted); margin-top: 4px; }

#cal06Events {
    max-height: 260px;
    overflow-y: auto;
    padding-right: 4px;
}

#cal06Events::-webkit-scrollbar {
    width: 5px;
}
#cal06Events::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}
#cal06Events::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
#cal06Events::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.cal06__ev {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
}
.cal06__ev:hover { background-color: var(--surface); }
.cal06__ev:last-child { border-bottom: none; }
.cal06__ev-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

.cal06__ev-name { font-size: 12px; font-weight: 600; color: var(--ink); }
.cal06__ev-time { font-size: 10px; color: var(--muted); }
.cal06__empty { font-size: 12px; color: var(--muted); padding: 8px 0; font-style: italic; }

@media (max-width: 768px) {
    .cal06__layout { grid-template-columns: 1fr; }
    .cal06__side { display: none; }
}
</style>

<div id="modal-schedule" class="modal" data-user-id="{{ auth()->id() }}" data-user-role="{{ auth()->user()->role_id }}">
    <div class="modal-content" style="max-width: 860px; width: 95%;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 15px;">
            <h3 style="margin: 0;">Agenda de Actividades</h3>
            <span class="close btn-cancel" data-modal-close style="cursor: pointer; font-size: 1.5rem; line-height: 1; background: transparent; border: none; padding: 0;">&times;</span>
        </div>

        <div class="modal-body">
            <div class="cal06">
                <div class="cal06__layout">
                    <div class="cal06__main">
                        <div class="cal06__top">
                            <div class="cal06__month-block">
                                <div class="cal06__month-label" id="cal06Month"></div>
                                <div class="cal06__year-label" id="cal06Year"></div>
                            </div>
                            <div class="cal06__top-right">
                                <div class="cal06__nav-row">
                                    <button aria-label="Mes anterior" class="cal06__nav-btn" id="cal06Prev">‹</button>
                                    <button aria-label="Mes siguiente" class="cal06__nav-btn" id="cal06Next">›</button>
                                </div>
                                <button class="cal06__today-pill" id="cal06Today">Hoy</button>
                            </div>
                        </div>
                        <div class="cal06__dow">
                            <div class="cal06__dow-lbl">D</div>
                            <div class="cal06__dow-lbl">L</div>
                            <div class="cal06__dow-lbl">M</div>
                            <div class="cal06__dow-lbl">X</div>
                            <div class="cal06__dow-lbl">J</div>
                            <div class="cal06__dow-lbl">V</div>
                            <div class="cal06__dow-lbl">S</div>
                        </div>
                        
                        <div class="cal06__grid" id="cal06Grid"></div>
                    </div>

                    <div class="cal06__side">
                        <div class="cal06__side-card">
                            <div class="cal06__side-label">Día seleccionado</div>
                            <div class="cal06__date-big" id="cal06DateBig"></div>
                            <div class="cal06__date-info" id="cal06DateInfo"></div>
                        </div>
                        <div class="cal06__side-card">
                            <div class="cal06__side-label">Próximas actividades</div>
                            <div id="cal06Events">
                                @forelse($events ?? [] as $event)
                                    <div class="cal06__ev" data-id="{{ $event->id }}">
                                        <div class="cal06__ev-dot" style="background-color: {{ $event->color }};"></div>
                                        <div class="cal06__ev-body">
                                            <div class="cal06__ev-name">{{ $event->title }}</div>
                                            <div class="cal06__ev-time">
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="cal06__empty">No hay actividades agendadas</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 15px;">
            <button type="button" class="btn btn-cancel" data-modal-close>Cerrar</button>
        </div>
    </div>
</div>