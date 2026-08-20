export function initSelectFinder(inputId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);

    if (!input || !select) return;

    input.addEventListener('input', (e) => {
        const filter = e.target.value.toLowerCase().trim();
        const options = select.options;

        let firstMatchIndex = -1;

        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.value === '') continue;

            const text = option.textContent.toLowerCase();
            if (text.includes(filter)) {
                option.style.display = '';
                if (firstMatchIndex === -1 && filter !== '') {
                    firstMatchIndex = i;
                }
            } else {
                option.style.display = 'none';
            }
        }

        if (firstMatchIndex !== -1) {
            select.selectedIndex = firstMatchIndex;
            select.dispatchEvent(new Event('change'));
        } else if (filter === '') {
            select.selectedIndex = 0;
            select.dispatchEvent(new Event('change'));
        }
    });
}