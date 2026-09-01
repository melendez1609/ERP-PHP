let cart = [];
let lastTicketUrl = null;

export function initPOS() {
    const catalog = document.getElementById('pos-catalog-section');
    const searchInput = document.getElementById('pos-search-input');
    const closePreviewBtn = document.getElementById('close-preview-modal');
    const printFromPreviewBtn = document.getElementById('btn-print-from-preview');

    const paymentModal = document.getElementById('modal-payment');
    const receivedInput = document.getElementById('pay-modal-received');
    const paymentForm = document.getElementById('form-process-payment');

    catalog?.addEventListener('click', (e) => {
        const card = e.target.closest('.pos-product-card');
        if (!card) return;

        addToCart({
            id: parseInt(card.dataset.id),
            code: card.dataset.code,
            name: card.dataset.name,
            price: parseFloat(card.dataset.price),
            stock: parseInt(card.dataset.stock)
        });
    });

    searchInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = searchInput.value.trim().toLowerCase();

            if (query === '') {
                searchInput.blur();
                openPaymentModal();
                return;
            }

            const cards = Array.from(document.querySelectorAll('.pos-product-card'));
            const match = cards.find(c => 
                c.dataset.code.toLowerCase() === query || 
                c.dataset.name.toLowerCase().includes(query)
            );

            if (match) {
                match.click();
                searchInput.value = '';
            } else {
                alert('Producto no encontrado');
            }
        }
    });

    receivedInput?.addEventListener('input', calculateChange);

    paymentForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        processSale();
    });

    closePreviewBtn?.addEventListener('click', () => {
        const modal = document.getElementById('modal-ticket-preview');
        if (modal) modal.style.display = 'none';
    });

    printFromPreviewBtn?.addEventListener('click', () => {
        const iframe = document.getElementById('preview-ticket-iframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.print();
        }
    });
}

export function openPaymentModal() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }

    const total = getCartTotal();
    const modal = document.getElementById('modal-payment');
    const totalEl = document.getElementById('pay-modal-total');
    const receivedInput = document.getElementById('pay-modal-received');
    const changeEl = document.getElementById('pay-modal-change');
    const confirmBtn = document.getElementById('btn-confirm-payment');

    if (modal && totalEl && receivedInput && changeEl) {
        totalEl.textContent = `$${total.toFixed(2)}`;
        receivedInput.value = '';
        changeEl.textContent = '$0.00';
        if (confirmBtn) confirmBtn.disabled = true;

        modal.style.display = 'flex';
        modal.classList.add('active');
        setTimeout(() => receivedInput.focus(), 100);
    }
}

function calculateChange() {
    const total = getCartTotal();
    const receivedInput = document.getElementById('pay-modal-received');
    const changeEl = document.getElementById('pay-modal-change');
    const confirmBtn = document.getElementById('btn-confirm-payment');

    const received = parseFloat(receivedInput.value) || 0;
    const change = received - total;

    if (received >= total && total > 0) {
        changeEl.textContent = `$${change.toFixed(2)}`;
        changeEl.style.color = '#15803d';
        if (confirmBtn) confirmBtn.disabled = false;
    } else {
        changeEl.textContent = '$0.00';
        changeEl.style.color = '#dc2626';
        if (confirmBtn) confirmBtn.disabled = true;
    }
}

function getCartTotal() {
    return cart.reduce((sum, item) => sum + item.subtotal, 0);
}

export async function processSale() {
    if (cart.length === 0) return;

    const payload = {
        items: cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
        }))
    };

    try {
        const response = await fetch('/cash-register/sale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            const modal = document.getElementById('modal-payment');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('active');
            }

            const balanceElement = document.getElementById('pos-current-balance');
            if (balanceElement && data.new_balance) {
                balanceElement.textContent = `$${data.new_balance}`;
            }

            lastTicketUrl = data.ticket_url;
            printTicket(data.ticket_url);
            
            cart.forEach(item => {
                const card = document.querySelector(`.pos-product-card[data-id="${item.product_id}"]`);
                if (card) {
                    let currentStock = parseInt(card.dataset.stock) || 0;
                    let newStock = Math.max(0, currentStock - item.quantity);
                    card.dataset.stock = newStock;

                    if (newStock <= 0) {
                        card.style.opacity = '0.4';
                        card.style.pointerEvents = 'none';
                        let badge = card.querySelector('.out-of-stock-badge');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'out-of-stock-badge';
                            badge.style.cssText = 'position:absolute; background:#dc2626; color:white; font-size:10px; padding:2px 6px; top:5px; right:5px; border-radius:4px; font-weight:bold; z-index:10;';
                            badge.textContent = 'AGOTADO';
                            card.style.position = 'relative';
                            card.appendChild(badge);
                        }
                    }
                }
            });

            cart = [];
            renderCart();

            const searchInput = document.getElementById('pos-search-input');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
        } else {
            alert('Error: ' + data.message);
        }
    } catch (err) {
        alert('Ocurrió un error al procesar el pago.');
    }
}

function addToCart(product) {
    const card = document.querySelector(`.pos-product-card[data-id="${product.id}"]`);
    const totalStock = card ? parseInt(card.dataset.stock) : product.stock;

    const existing = cart.find(item => item.product_id === product.id);
    const quantityInCart = existing ? existing.quantity : 0;

    if (quantityInCart + 1 > totalStock) {
        alert(`Stock insuficiente. Stock disponible: ${totalStock - quantityInCart}`);
        return;
    }

    if (existing) {
        existing.quantity++;
        existing.subtotal = existing.quantity * existing.price;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1,
            subtotal: product.price,
            stock: totalStock
        });
    }
    renderCart();
    updateCardVisualState(product.id, totalStock);
}

function updateCardVisualState(productId, totalStock) {
    const card = document.querySelector(`.pos-product-card[data-id="${productId}"]`);
    if (!card) return;

    const cartItem = cart.find(item => item.product_id === productId);
    const quantityInCart = cartItem ? cartItem.quantity : 0;
    const remaining = totalStock - quantityInCart;

    if (remaining <= 0) {
        card.style.opacity = '0.4';
        card.style.pointerEvents = 'none';
        
        let badge = card.querySelector('.out-of-stock-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'out-of-stock-badge';
            badge.style.cssText = 'position:absolute; background:#dc2626; color:white; font-size:10px; padding:2px 6px; top:5px; right:5px; border-radius:4px; font-weight:bold; z-index:10;';
            badge.textContent = 'AGOTADO';
            card.style.position = 'relative';
            card.appendChild(badge);
        }
    } else {
        card.style.opacity = '1';
        card.style.pointerEvents = 'auto';
        const badge = card.querySelector('.out-of-stock-badge');
        if (badge) badge.remove();
    }
}

function renderCart() {
    const ticketContainer = document.getElementById('pos-ticket-items');
    const totalElement = document.getElementById('pos-total-amount');
    if (!ticketContainer || !totalElement) return;

    ticketContainer.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.subtotal;
        const row = document.createElement('div');
        row.className = 'ticket-item';
        row.innerHTML = `
            <span><strong>${item.quantity}</strong></span>
            <span class="col-name">${item.name}</span>
            <span>$${item.price.toFixed(2)}</span>
            <span>$${item.subtotal.toFixed(2)}</span>
        `;
        row.addEventListener('click', () => {
            const productId = item.product_id;
            cart.splice(index, 1);
            renderCart();
            
            const card = document.querySelector(`.pos-product-card[data-id="${productId}"]`);
            if (card) {
                updateCardVisualState(productId, parseInt(card.dataset.stock));
            }
        });
        ticketContainer.appendChild(row);
    });

    totalElement.textContent = `$${total.toFixed(2)}`;
}

function printTicket(url) {
    const iframe = document.getElementById('ticket-print-frame');
    if (!iframe) return;

    iframe.src = url;
    iframe.onload = () => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    };
}

export function handleNumericInput(key) {
    if (cart.length === 0 || key === '.') return;

    const lastItem = cart[cart.length - 1];
    const card = document.querySelector(`.pos-product-card[data-id="${lastItem.product_id}"]`);
    const totalStock = card ? parseInt(card.dataset.stock) : lastItem.stock;

    let currentQtyStr = lastItem.quantity.toString();
    let newQty = parseInt(currentQtyStr + key);

    if (newQty > totalStock) {
        alert(`Stock máximo disponible: ${totalStock}`);
        return;
    }

    lastItem.quantity = newQty;
    lastItem.subtotal = lastItem.quantity * lastItem.price;
    renderCart();
    updateCardVisualState(lastItem.product_id, totalStock);
}

export function clearCart() {
    cart = [];
    renderCart();
    document.querySelectorAll('.pos-product-card').forEach(card => {
        // Solo restauramos las tarjetas que aún tienen stock mayor a 0
        const stock = parseInt(card.dataset.stock) || 0;
        if (stock > 0) {
            card.style.opacity = '1';
            card.style.pointerEvents = 'auto';
            const badge = card.querySelector('.out-of-stock-badge');
            if (badge) badge.remove();
        }
    });
}

export function removeLastItem() {
    if (cart.length > 0) {
        const lastItem = cart.pop();
        renderCart();
        const card = document.querySelector(`.pos-product-card[data-id="${lastItem.product_id}"]`);
        if (card) {
            updateCardVisualState(lastItem.product_id, parseInt(card.dataset.stock));
        }
    }
}

export async function handleReprint() {
    if (cart.length === 0) {
        alert('El carrito está vacío para previsualizar.');
        return;
    }

    const payload = {
        items: cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
        }))
    };

    try {
        const response = await fetch('/cash-register/preview-ticket', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const html = await response.text();
        const iframePreview = document.getElementById('preview-ticket-iframe');
        const modal = document.getElementById('modal-ticket-preview');

        if (iframePreview && modal) {
            modal.style.display = 'flex';
            const doc = iframePreview.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();
        }
    } catch (err) {
        alert('Ocurrió un error al generar la vista previa del ticket.');
    }
}

export function initPosOptions() {
    const modal = document.getElementById('modal-alert');
    if (!modal) return;

    const autoOpen = modal.dataset.autoOpen === 'true';
    if (!autoOpen) return;

    const titleEl = modal.querySelector('.modal-title');
    const messageEl = modal.querySelector('.modal-message');
    const confirmBtn = modal.querySelector('.btn-alert-confirm');
    const cancelBtn = modal.querySelector('.modal-footer .btn-cancel');
    const isDiscrepancy = modal.dataset.isDiscrepancy === 'true';

    if (titleEl && modal.dataset.title) {
        titleEl.textContent = modal.dataset.title;
    }

    if (messageEl && modal.dataset.message) {
        messageEl.textContent = modal.dataset.message;
    }

    if (isDiscrepancy) {
        if (confirmBtn) {
            confirmBtn.style.display = 'inline-block';
            confirmBtn.textContent = 'Confirmar Cierre';
        }
        if (cancelBtn) {
            cancelBtn.textContent = 'Cancelar';
        }
    } else {
        if (confirmBtn) {
            confirmBtn.style.display = 'none';
        }
        if (cancelBtn) {
            cancelBtn.textContent = 'Entendido';
        }
    }

    modal.style.display = 'flex';
    modal.classList.add('active', 'show');
}