export function initSelectFinder(inputId, selectId) {
    const searchInput = document.getElementById(inputId);
    const selectElement = document.getElementById(selectId);

    if (!searchInput || !selectElement) return;

    const originalOptions = Array.from(selectElement.options).map(opt => ({
        value: opt.value,
        text: opt.text,
        dataset: { ...opt.dataset }
    }));

    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase().trim();

        selectElement.innerHTML = '';

        originalOptions.forEach(optData => {
            const textLower = optData.text.toLowerCase();
            
            if (optData.value === "" || textLower.includes(searchText)) {
                const newOption = document.createElement('option');
                newOption.value = optData.value;
                newOption.text = optData.text;

                Object.keys(optData.dataset).forEach(key => {
                    newOption.dataset[key] = optData.dataset[key];
                });

                selectElement.appendChild(newOption);
            }
        });

        selectElement.value = "";
    });
}