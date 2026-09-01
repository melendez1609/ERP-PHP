export function initCreateInventory() {
    const costInput = document.getElementById('cost');
    const profitInput = document.getElementById('profit_percentage');
    const vatSelect = document.getElementById('vat_id');
    const priceInput = document.getElementById('price');

    if (!costInput || !profitInput || !vatSelect || !priceInput) return;

    const calculatePrice = () => {
        const cost = parseFloat(costInput.value) || 0;
        const profit = parseFloat(profitInput.value) || 0;
        
        let vatRate = 0;
        if (vatSelect.selectedIndex >= 0 && vatSelect.options.length > 0) {
            const selectedText = vatSelect.options[vatSelect.selectedIndex].text;
            const match = selectedText.match(/\((\d+(\.\d+)?)%\)/);
            if (match) {
                vatRate = parseFloat(match[1]);
            }
        }

        const costWithProfit = cost * (1 + (profit / 100));
        const finalPrice = costWithProfit * (1 + (vatRate / 100));

        if (cost > 0) {
            priceInput.value = finalPrice.toFixed(2);
        } else {
            priceInput.value = '';
        }
    };

    costInput.addEventListener('input', calculatePrice);
    profitInput.addEventListener('input', calculatePrice);
    vatSelect.addEventListener('change', calculatePrice);
}