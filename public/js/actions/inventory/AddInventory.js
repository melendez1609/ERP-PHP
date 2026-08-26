export function initAddInventory() {
    const restockBtn = document.querySelector('[data-modal-target="modal-restock"]');
    const productSelect = document.getElementById('restock_product_id');
    const searchInput = document.getElementById('restock-product-search');
    
    const costInput = document.getElementById('restock_cost');
    const profitInput = document.getElementById('restock_profit_percentage');
    const vatSelect = document.getElementById('restock_vat_id');
    const priceInput = document.getElementById('restock_price');

    if (!productSelect) return;

    let productsList = [];

    const fetchActiveProducts = async () => {
        productSelect.innerHTML = '<option value="">Cargando productos...</option>';
        try {
            const response = await fetch('/inventory/active-products');
            if (response.ok) {
                productsList = await response.json();
                renderProductOptions(productsList);
            } else {
                productSelect.innerHTML = '<option value="">Error al cargar productos</option>';
            }
        } catch (error) {
            console.error('Error al obtener productos activos:', error);
            productSelect.innerHTML = '<option value="">Error al cargar productos</option>';
        }
    };

    const renderProductOptions = (products) => {
        productSelect.innerHTML = '<option value="">-- Seleccionar Producto --</option>';
        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            const supplierName = product.supplier ? product.supplier.name : 'Sin proveedor';
            option.textContent = `${product.code} - ${product.name} (${supplierName})`;
            productSelect.appendChild(option);
        });
    };

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase().trim();
            const filtered = productsList.filter(product => {
                const code = product.code ? product.code.toLowerCase() : '';
                const name = product.name ? product.name.toLowerCase() : '';
                const supplier = product.supplier ? product.supplier.name.toLowerCase() : '';
                return code.includes(term) || name.includes(term) || supplier.includes(term);
            });
            renderProductOptions(filtered);
        });
    }

    const calculatePrice = () => {
        const cost = parseFloat(costInput?.value) || 0;
        const profit = parseFloat(profitInput?.value) || 0;
        
        let vatRate = 0;
        if (vatSelect && vatSelect.selectedIndex >= 0) {
            const selectedText = vatSelect.options[vatSelect.selectedIndex].text;
            const match = selectedText.match(/\((\d+(\.\d+)?)%\)/);
            if (match) {
                vatRate = parseFloat(match[1]);
            }
        }

        const costWithProfit = cost * (1 + profit / 100);
        const finalPrice = costWithProfit * (1 + vatRate / 100);

        if (priceInput) {
            priceInput.value = finalPrice > 0 ? finalPrice.toFixed(2) : '';
        }
    };

    if (costInput) costInput.addEventListener('input', calculatePrice);
    if (profitInput) profitInput.addEventListener('input', calculatePrice);
    if (vatSelect) vatSelect.addEventListener('change', calculatePrice);

    if (restockBtn) {
        restockBtn.addEventListener('click', () => {
            fetchActiveProducts();
            if (searchInput) searchInput.value = '';
        });
    }
}