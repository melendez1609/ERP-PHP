export function initPOSKeyboard(onEnterPay, onNumInput, onClear, onBackspace, onPrintLast) {
    const container = document.querySelector('.erp-cash-register-container');
    if (!container) return;

    const animateButton = (btn) => {
        if (!btn) return;
        btn.classList.add('key-pressed');
        setTimeout(() => btn.classList.remove('key-pressed'), 150);
    };

    const findButton = (key) => {
        if (key === 'Enter') return document.getElementById('btn-pay');
        if (key === 'Escape' || key.toLowerCase() === 'c' || key === 'Delete') return document.getElementById('btn-clear');
        if (key === 'Backspace') return document.getElementById('btn-backspace');
        if (key.toLowerCase() === 'p' || key === '*') return document.getElementById('btn-print-last');
        return document.querySelector(`.num-key[data-key="${key}"]`);
    };

    document.querySelectorAll('.num-key').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.dataset.key) {
                animateButton(btn);
                onNumInput(btn.dataset.key);
            }
        });
    });

    document.getElementById('btn-clear')?.addEventListener('click', (e) => {
        animateButton(e.currentTarget);
        onClear();
    });

    document.getElementById('btn-backspace')?.addEventListener('click', (e) => {
        animateButton(e.currentTarget);
        onBackspace();
    });

    document.getElementById('btn-pay')?.addEventListener('click', (e) => {
        animateButton(e.currentTarget);
        onEnterPay();
    });

    document.getElementById('btn-print-last')?.addEventListener('click', (e) => {
        animateButton(e.currentTarget);
        if (onPrintLast) onPrintLast();
    });

    window.addEventListener('keydown', (e) => {
        const searchInput = document.getElementById('pos-search-input');
        const isSearchFocused = document.activeElement === searchInput;

        if (e.key === 'Enter') {
            if (isSearchFocused && searchInput.value.trim() !== '') return;
            e.preventDefault();
            animateButton(findButton('Enter'));
            onEnterPay();
            return;
        }

        if (isSearchFocused) return;

        if (e.key.toLowerCase() === 'p' || e.key === '*') {
            e.preventDefault();
            animateButton(findButton('p'));
            if (onPrintLast) onPrintLast();
        } else if (e.key === 'Escape' || e.key.toLowerCase() === 'c' || e.key === 'Delete') {
            e.preventDefault();
            animateButton(findButton('Escape'));
            onClear();
        } else if (e.key === 'Backspace') {
            e.preventDefault();
            animateButton(findButton('Backspace'));
            onBackspace();
        } else if (/^[0-9.]$/.test(e.key)) {
            animateButton(findButton(e.key));
            onNumInput(e.key);
        }
    });
}