export function initScheduleDate() {
    const modal = document.getElementById('modal-schedule');
    if (!modal) return;

    const authUserId = modal.dataset.userId ? parseInt(modal.dataset.userId) : null;

    const monthEl = document.getElementById('cal06Month');
    const yearEl = document.getElementById('cal06Year');
    const gridEl = document.getElementById('cal06Grid');
    const prevBtn = document.getElementById('cal06Prev');
    const nextBtn = document.getElementById('cal06Next');
    const todayBtn = document.getElementById('cal06Today');
    const dateBigEl = document.getElementById('cal06DateBig');
    const dateInfoEl = document.getElementById('cal06DateInfo');
    const eventsContainer = document.getElementById('cal06Events');

    const createForm = document.getElementById('form-create-event');
    const editForm = document.getElementById('form-edit-event');
    const deleteBtn = document.getElementById('btn-delete-event');
    const alertModal = document.getElementById('modal-alert');

    const MONTHS = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const DAYS_FULL = [
        'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'
    ];

    let today = new Date();
    let viewDate = new Date();
    let selectedDate = new Date();
    let eventsData = [];
    let clickTimer = null;

    async function loadEvents() {
        try {
            const response = await fetch('/schedules', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (response.ok) {
                eventsData = await response.json();
                renderCalendar();
                updateSideCard(selectedDate);
            }
        } catch (error) {
            console.error('Error al obtener eventos:', error);
        }
    }

    function renderCalendar() {
        const year = viewDate.getFullYear();
        const month = viewDate.getMonth();

        if (monthEl) monthEl.textContent = MONTHS[month];
        const quarter = Math.floor(month / 3) + 1;
        if (yearEl) yearEl.textContent = `${year} · Q${quarter}`;

        if (!gridEl) return;
        gridEl.innerHTML = '';

        const firstDayIndex = new Date(year, month, 1).getDay();
        const totalDaysMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthLastDay = new Date(year, month, 0).getDate();

        for (let i = firstDayIndex; i > 0; i--) {
            const prevDay = prevMonthLastDay - i + 1;
            const cell = document.createElement('div');
            cell.className = 'cal06__cell other';
            cell.innerHTML = `<div class="cal06__cell-n">${prevDay}</div>`;
            gridEl.appendChild(cell);
        }

        for (let day = 1; day <= totalDaysMonth; day++) {
            const cell = document.createElement('div');
            cell.className = 'cal06__cell';

            const isToday = day === today.getDate() && 
                            month === today.getMonth() && 
                            year === today.getFullYear();

            const isSelected = day === selectedDate.getDate() && 
                               month === selectedDate.getMonth() && 
                               year === selectedDate.getFullYear();

            if (isToday) cell.classList.add('today');
            if (isSelected) cell.classList.add('selected');

            const currentFormattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayEvents = eventsData.filter(e => e.event_date === currentFormattedDate);

            let pipsHtml = '';
            if (dayEvents.length > 0) {
                pipsHtml = '<div class="cal06__pips">';
                dayEvents.forEach(ev => {
                    pipsHtml += `<span class="cal06__pip" style="background-color: ${ev.color || '#3b82f6'};"></span>`;
                });
                pipsHtml += '</div>';
            }

            cell.innerHTML = `<div class="cal06__cell-n">${day}</div>${pipsHtml}`;

            cell.addEventListener('click', () => {
                if (clickTimer) clearTimeout(clickTimer);
                clickTimer = setTimeout(() => {
                    selectedDate = new Date(year, month, day);
                    gridEl.querySelectorAll('.cal06__cell').forEach(c => c.classList.remove('selected'));
                    cell.classList.add('selected');
                    updateSideCard(selectedDate);
                }, 200);
            });

            cell.addEventListener('dblclick', () => {
                if (clickTimer) clearTimeout(clickTimer);
                selectedDate = new Date(year, month, day);
                gridEl.querySelectorAll('.cal06__cell').forEach(c => c.classList.remove('selected'));
                cell.classList.add('selected');
                updateSideCard(selectedDate);
                openCreateModal(selectedDate);
            });

            gridEl.appendChild(cell);
        }

        const totalCellsSoFar = firstDayIndex + totalDaysMonth;
        const nextDaysNeeded = (totalCellsSoFar > 35 ? 42 : 35) - totalCellsSoFar;

        for (let day = 1; day <= nextDaysNeeded; day++) {
            const cell = document.createElement('div');
            cell.className = 'cal06__cell other';
            cell.innerHTML = `<div class="cal06__cell-n">${day}</div>`;
            gridEl.appendChild(cell);
        }
    }

    function updateSideCard(date) {
        if (dateBigEl) {
            dateBigEl.textContent = String(date.getDate()).padStart(2, '0');
        }
        if (dateInfoEl) {
            const dayName = DAYS_FULL[date.getDay()];
            const monthName = MONTHS[date.getMonth()].toLowerCase();
            const year = date.getFullYear();
            dateInfoEl.textContent = `${dayName}, ${date.getDate()} de ${monthName} de ${year}`;
        }

        renderEventsListForDate(date);
    }

    function renderEventsListForDate(date) {
        if (!eventsContainer) return;

        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const dd = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${yyyy}-${mm}-${dd}`;

        const filtered = eventsData.filter(e => e.event_date === formattedDate);

        eventsContainer.innerHTML = '';

        if (filtered.length === 0) {
            eventsContainer.innerHTML = '<div class="cal06__empty">No hay eventos agendados</div>';
            return;
        }

        let globalDropdown = document.getElementById('cal06-global-dropdown');
        if (!globalDropdown) {
            globalDropdown = document.createElement('div');
            globalDropdown.id = 'cal06-global-dropdown';
            globalDropdown.style.cssText = 'display: none; position: fixed; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 99999; min-width: 100px; padding: 4px 0;';
            globalDropdown.innerHTML = `
                <button type="button" class="cal06__dropdown-item btn-view" style="width: 100%; text-align: left; background: transparent; border: none; padding: 8px 14px; font-size: 12px; color: #0f172a; cursor: pointer; font-weight: 500;">Ver</button>
                <button type="button" class="cal06__dropdown-item btn-edit" style="width: 100%; text-align: left; background: transparent; border: none; padding: 8px 14px; font-size: 12px; color: #0f172a; cursor: pointer; font-weight: 500;">Editar</button>
            `;
            document.body.appendChild(globalDropdown);

            globalDropdown.querySelectorAll('.cal06__dropdown-item').forEach(item => {
                item.addEventListener('mouseenter', () => item.style.backgroundColor = '#f1f5f9');
                item.addEventListener('mouseleave', () => item.style.backgroundColor = 'transparent');
            });
        }

        let activeEventForDropdown = null;

        globalDropdown.querySelector('.btn-view').onclick = (evt) => {
            evt.stopPropagation();
            globalDropdown.style.display = 'none';
            if (activeEventForDropdown) openViewModal(activeEventForDropdown);
        };

        globalDropdown.querySelector('.btn-edit').onclick = (evt) => {
            evt.stopPropagation();
            globalDropdown.style.display = 'none';
            if (activeEventForDropdown) openEditModal(activeEventForDropdown);
        };

        filtered.forEach(event => {
            const evItem = document.createElement('div');
            evItem.className = 'cal06__ev';
            evItem.setAttribute('data-id', event.id);

            let creatorHtml = '';
            if (event.user && event.user.name && event.user_id !== authUserId) {
                creatorHtml = `<div class="cal06__ev-creator" style="font-size: 9px; color: #3b82f6; font-weight: 600; margin-top: 2px;">Creado por: ${event.user.name}</div>`;
            }

            evItem.innerHTML = `
                <div class="cal06__ev-dot" style="background-color: ${event.color || '#3b82f6'};"></div>
                <div class="cal06__ev-body" style="flex: 1; cursor: pointer;">
                    <div class="cal06__ev-name">${event.title}</div>
                    <div class="cal06__ev-time">${event.event_time}</div>
                    ${creatorHtml}
                </div>
                <div class="cal06__ev-menu-wrapper" style="position: relative;">
                    <button type="button" class="cal06__ev-options-btn" style="background: transparent; border: none; cursor: pointer; padding: 4px 8px; color: #64748b; font-size: 16px; font-weight: bold;" aria-label="Opciones">&#8942;</button>
                </div>
            `;

            evItem.querySelector('.cal06__ev-body').addEventListener('click', (evt) => {
                evt.stopPropagation();
                globalDropdown.style.display = 'none';
                openViewModal(event);
            });

            const optionsBtn = evItem.querySelector('.cal06__ev-options-btn');
            optionsBtn.addEventListener('click', (evt) => {
                evt.stopPropagation();
                activeEventForDropdown = event;
                const rect = optionsBtn.getBoundingClientRect();
                globalDropdown.style.display = 'block';
                globalDropdown.style.top = `${rect.bottom + 4}px`;
                globalDropdown.style.left = `${rect.right - 100}px`;
            });

            eventsContainer.appendChild(evItem);
        });

        document.addEventListener('click', () => {
            if (globalDropdown) globalDropdown.style.display = 'none';
        });
    }

    function openViewModal(event) {
        const viewModal = document.getElementById('modal-view-schedule');
        if (!viewModal) return;

        const titleEl = viewModal.querySelector('#view_event_title');
        const dateEl = viewModal.querySelector('#view_event_date');
        const timeEl = viewModal.querySelector('#view_event_time');
        const descEl = viewModal.querySelector('#view_event_description');
        const creatorWrapper = viewModal.querySelector('#view_event_creator_wrapper');
        const creatorEl = viewModal.querySelector('#view_event_creator');

        if (titleEl) titleEl.textContent = event.title;
        if (dateEl) dateEl.textContent = event.event_date;
        if (timeEl) timeEl.textContent = event.event_time;
        if (descEl) descEl.textContent = event.description || 'Sin descripción detallada';

        if (creatorWrapper && creatorEl) {
            if (event.user && event.user.name && event.user_id !== authUserId) {
                creatorEl.textContent = event.user.name;
                creatorWrapper.style.display = 'block';
            } else {
                creatorWrapper.style.display = 'none';
            }
        }

        openCustomModal(viewModal, '9999');
    }

    function openCreateModal(date) {
        const createModal = document.getElementById('modal-create-event');
        if (!createModal) return;

        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const dd = String(date.getDate()).padStart(2, '0');
        const formattedDate = `${yyyy}-${mm}-${dd}`;

        const dateInput = createModal.querySelector('#create_event_date');
        if (dateInput) dateInput.value = formattedDate;

        openCustomModal(createModal, '9999');
    }

    function openEditModal(event) {
        const editModal = document.getElementById('modal-edit-event');
        if (!editModal) return;

        const form = editModal.querySelector('#form-edit-event');
        if (form) form.action = `/schedules/${event.id}`;

        const idInput = editModal.querySelector('#edit_event_id');
        const titleInput = editModal.querySelector('#edit_title');
        const dateInput = editModal.querySelector('#edit_event_date');
        const timeInput = editModal.querySelector('#edit_event_time');
        const colorInput = editModal.querySelector('#edit_color');
        const descInput = editModal.querySelector('#edit_description');

        if (idInput) idInput.value = event.id;
        if (titleInput) titleInput.value = event.title;
        if (dateInput) dateInput.value = event.event_date;
        if (timeInput) timeInput.value = event.event_time;
        if (colorInput) colorInput.value = event.color;
        if (descInput) descInput.value = event.description || '';

        openCustomModal(editModal, '9999');
    }

    function openCustomModal(modalEl, zIndex = '9999') {
        if (!modalEl) return;
        modalEl.classList.add('active', 'show');
        modalEl.style.display = 'flex';
        modalEl.style.zIndex = zIndex;
    }

    function closeCustomModal(modalEl) {
        if (!modalEl) return;
        modalEl.classList.remove('active', 'show');
        modalEl.style.display = 'none';
        modalEl.style.zIndex = '';
    }

    function triggerCustomConfirm(message, onConfirm) {
        if (!alertModal) {
            if (confirm(message)) onConfirm();
            return;
        }

        const messageEl = alertModal.querySelector('.alert-message') || alertModal.querySelector('p');
        const footer = alertModal.querySelector('.modal-footer') || alertModal;

        if (messageEl) messageEl.textContent = message;

        const cancelBtn = footer.querySelector('.btn-cancel');
        const acceptBtn = footer.querySelector('.btn-accept') || 
                          footer.querySelector('.btn-save') || 
                          footer.querySelector('.btn-confirm') || 
                          footer.querySelectorAll('button')[1];

        openCustomModal(alertModal, '10050');

        if (cancelBtn) {
            cancelBtn.setAttribute('type', 'button');
            cancelBtn.textContent = 'Cancelar';
            cancelBtn.style.setProperty('background-color', '#e2e8f0', 'important');
            cancelBtn.style.setProperty('color', '#1e2b4f', 'important');
            cancelBtn.style.setProperty('border', 'none', 'important');
            cancelBtn.style.setProperty('padding', '8px 18px', 'important');
            cancelBtn.style.setProperty('border-radius', '8px', 'important');
            cancelBtn.style.setProperty('font-weight', '700', 'important');
            cancelBtn.style.setProperty('cursor', 'pointer', 'important');
            cancelBtn.style.setProperty('margin', '0', 'important');

            cancelBtn.onmouseenter = () => cancelBtn.style.setProperty('background-color', '#cbd5e1', 'important');
            cancelBtn.onmouseleave = () => cancelBtn.style.setProperty('background-color', '#e2e8f0', 'important');
        }

        if (acceptBtn) {
            const newAcceptBtn = acceptBtn.cloneNode(true);
            newAcceptBtn.setAttribute('type', 'button');
            newAcceptBtn.textContent = 'Confirmar';

            const setNavyStyle = () => {
                newAcceptBtn.style.setProperty('background-color', '#1e2b4f', 'important');
                newAcceptBtn.style.setProperty('color', '#ffffff', 'important');
                newAcceptBtn.style.setProperty('border', 'none', 'important');
                newAcceptBtn.style.setProperty('padding', '8px 18px', 'important');
                newAcceptBtn.style.setProperty('border-radius', '8px', 'important');
                newAcceptBtn.style.setProperty('font-weight', '700', 'important');
                newAcceptBtn.style.setProperty('cursor', 'pointer', 'important');
                newAcceptBtn.style.setProperty('margin', '0', 'important');
            };

            setNavyStyle();

            newAcceptBtn.onmouseenter = () => newAcceptBtn.style.setProperty('background-color', '#141c36', 'important');
            newAcceptBtn.onmouseleave = () => setNavyStyle();

            acceptBtn.parentNode.replaceChild(newAcceptBtn, acceptBtn);

            newAcceptBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                closeCustomModal(alertModal);
                await onConfirm();
            });
        }
    }

    document.querySelectorAll('#modal-create-event [data-modal-close], #modal-edit-event [data-modal-close], #modal-view-schedule [data-modal-close], #modal-alert [data-modal-close]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const parentModal = btn.closest('.modal');
            if (parentModal) closeCustomModal(parentModal);
        });
    });

    [document.getElementById('modal-create-event'), document.getElementById('modal-edit-event'), document.getElementById('modal-view-schedule'), alertModal].forEach(m => {
        if (m) {
            m.addEventListener('click', (e) => {
                if (e.target === m) closeCustomModal(m);
            });
        }
    });

    if (createForm) {
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                if (response.ok) {
                    closeCustomModal(document.getElementById('modal-create-event'));
                    this.reset();
                    await loadEvents();
                }
            } catch (err) {
                console.error('Error al guardar evento:', err);
            }
        });
    }

    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                if (response.ok) {
                    closeCustomModal(document.getElementById('modal-edit-event'));
                    await loadEvents();
                }
            } catch (err) {
                console.error('Error al actualizar evento:', err);
            }
        });
    }

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const editModal = document.getElementById('modal-edit-event');
            const eventId = document.getElementById('edit_event_id')?.value;
            const token = editModal?.querySelector('input[name="_token"]')?.value;

            if (!eventId) return;

            triggerCustomConfirm('¿Deseas eliminar este evento?', async () => {
                try {
                    const response = await fetch(`/schedules/${eventId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        closeCustomModal(editModal);
                        await loadEvents();
                    }
                } catch (err) {
                    console.error('Error al eliminar evento:', err);
                }
            });
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            viewDate.setMonth(viewDate.getMonth() - 1);
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            viewDate.setMonth(viewDate.getMonth() + 1);
            renderCalendar();
        });
    }

    if (todayBtn) {
        todayBtn.addEventListener('click', () => {
            today = new Date();
            viewDate = new Date();
            selectedDate = new Date();
            renderCalendar();
            updateSideCard(selectedDate);
        });
    }

    // Listener de Laravel Reverb en tiempo real
    if (window.Echo) {
        window.Echo.channel('schedules-channel')
            .listen('.ScheduleActionBroadcast', (e) => {
                const { schedule, action } = e;

                if (action === 'created') {
                    if (!eventsData.find(ev => ev.id === schedule.id)) {
                        eventsData.push(schedule);
                    }
                } else if (action === 'updated') {
                    const index = eventsData.findIndex(ev => ev.id === schedule.id);
                    if (index !== -1) {
                        eventsData[index] = schedule;
                    } else {
                        eventsData.push(schedule);
                    }
                } else if (action === 'deleted') {
                    eventsData = eventsData.filter(ev => ev.id !== schedule.id);
                }

                renderCalendar();
                updateSideCard(selectedDate);
            });
    }

    loadEvents();
}