(function () {
    'use strict';

    const config = window.QrCustomerConfig || {};

    const tableNumber = String(config.tableNumber || '');
    const csrfToken = config.csrfToken || '';
    const checkoutUrl = config.checkoutUrl || '/qr/order';

    const cartStorageKey = config.cartStorageKey || ('qr_cart_meja_' + tableNumber);
    const orderStorageKey = config.orderStorageKey || ('qr_orders_meja_' + tableNumber);
    const paymentOptions = config.paymentOptions || {};
    const defaultPaymentMethod = Object.keys(paymentOptions)[0] || 'cash';

    let cart = loadJson(cartStorageKey, []);
    let orders = loadJson(orderStorageKey, []);
    let activeCategory = 'semua';
    let isCheckoutLoading = false;

    let order = {
        customer_name: '',
        customer_phone: '',
        discount: 0,
        payment_method: defaultPaymentMethod,
        customer_note: ''
    };

    document.addEventListener('DOMContentLoaded', initQrCustomerOrder);

    function initQrCustomerOrder() {
        bindAddCartButtons();
        bindCategoryButtons();
        bindSearchInput();

        render();
        renderOrdersPage();
        filterMenu();
    }

// Debug: check route & CSRF
console.log('[QR Order] Setup complete', {
    url: checkoutUrl,
    csrf: csrfToken?.slice(0,10) + '...',
    hasCartKey: !!cartStorageKey,
    payOpts: Object.keys(paymentOptions)
});

function bindAddCartButtons() {
        document.querySelectorAll('.js-add-cart').forEach(button => {
            button.addEventListener('click', function () {
                addToCart(
                    Number(this.dataset.id),
                    this.dataset.name,
                    Number(this.dataset.price)
                );
            });
        });
    }

    function bindCategoryButtons() {
        document.querySelectorAll('.category-chip').forEach(button => {
            button.addEventListener('click', function () {
                filterCategory(this.dataset.categoryFilter, this);
            });
        });
    }

    function bindSearchInput() {
        const searchInput = document.getElementById('searchMenu');

        if (searchInput) {
            searchInput.addEventListener('input', filterMenu);
        }
    }

    function loadJson(key, fallback) {
        try {
            return JSON.parse(localStorage.getItem(key)) || fallback;
        } catch (e) {
            return fallback;
        }
    }

    function saveCart() {
        localStorage.setItem(cartStorageKey, JSON.stringify(cart));
    }

    function saveOrders() {
        localStorage.setItem(orderStorageKey, JSON.stringify(orders));
    }

    function showPage(page) {
        document.querySelectorAll('.page-view').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.bottom-nav-item').forEach(el => el.classList.remove('active'));

        const pageMenu = document.getElementById('pageMenu');
        const pageCart = document.getElementById('pageCart');
        const pageOrders = document.getElementById('pageOrders');

        const navMenu = document.getElementById('navMenu');
        const navCart = document.getElementById('navCart');
        const navOrders = document.getElementById('navOrders');

        if (page === 'menu') {
            pageMenu?.classList.add('active');
            navMenu?.classList.add('active');
        }

        if (page === 'cart') {
            pageCart?.classList.add('active');
            navCart?.classList.add('active');
            renderCartPage();
        }

        if (page === 'orders') {
            pageOrders?.classList.add('active');
            navOrders?.classList.add('active');
            renderOrdersPage();
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function addToCart(id, name, price) {
        if (!id || !name || price < 0) return;

        const item = cart.find(x => Number(x.id) === Number(id));

        if (item) {
            item.qty += 1;
        } else {
            cart.push({
                id: Number(id),
                name: String(name),
                price: Number(price),
                qty: 1,
                note: ''
            });
        }

        saveCart();
        render();
    }

    function changeQty(id, type) {
        const item = cart.find(x => Number(x.id) === Number(id));
        if (!item) return;

        if (type === 'plus') item.qty += 1;
        if (type === 'minus') item.qty -= 1;

        if (item.qty <= 0) {
            cart = cart.filter(x => Number(x.id) !== Number(id));
        }

        saveCart();
        render();
        renderCartPage();
    }

    function updateItemNote(id, value) {
        const item = cart.find(x => Number(x.id) === Number(id));
        if (!item) return;

        item.note = value || '';
        saveCart();
    }

    function updateOrderField(field, value) {
        if (['discount', 'tax', 'service'].includes(field)) {
            order[field] = Number(value) || 0;
        } else {
            order[field] = value || '';
        }

        renderCartSummaryOnly();
    }

    function calculate() {
        const subtotal = cart.reduce((total, item) => {
            return total + (Number(item.price) * Number(item.qty));
        }, 0);

        const discount = Math.max(Number(order.discount) || 0, 0);
        const afterDiscount = Math.max(subtotal - discount, 0);

        const taxRate = config.tax_enabled ? Math.max(Number(config.tax_rate) || 0, 0) : 0;
        const serviceRate = config.service_enabled ? Math.max(Number(config.service_rate) || 0, 0) : 0;

        const tax = config.tax_inclusive ? 0 : afterDiscount * taxRate;
        const service = config.service_inclusive ? 0 : afterDiscount * serviceRate;

        const grand_total = Math.max(afterDiscount + tax + service, 0);

        return {
            subtotal,
            discount,
            tax,
            service,
            grand_total,
            taxRate,
            serviceRate
        };
    }

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function getTotalQty() {
        return cart.reduce((total, item) => {
            return total + Number(item.qty || 0);
        }, 0);
    }

    function render() {
        const totalQty = getTotalQty();

        const cartCount = document.getElementById('cartCount');
        const navCartBadge = document.getElementById('navCartBadge');

        if (cartCount) {
            cartCount.innerText = totalQty;
            cartCount.classList.toggle('show', totalQty > 0);
        }

        if (navCartBadge) {
            navCartBadge.innerText = totalQty;
            navCartBadge.classList.toggle('show', totalQty > 0);
        }
    }

    function renderCartPage() {
        const el = document.getElementById('cartPageContent');
        if (!el) return;

        if (cart.length === 0) {
            el.innerHTML = `
                <div class="empty-state">
                    <div>
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 4h2l2.5 13h10L21 8H7" stroke-width="1.8"></path>
                                <circle cx="10" cy="20" r="1.2"></circle>
                                <circle cx="18" cy="20" r="1.2"></circle>
                            </svg>
                        </div>
                        <h5>Keranjang Kosong</h5>
                        <p>Tambahkan menu favorit kamu</p>
                        <button type="button" class="btn-blue" onclick="QrCustomerOrder.showPage('menu')">
                            Lihat Menu
                        </button>
                    </div>
                </div>
            `;
            return;
        }

        let html = `<div class="cart-page-list">`;

        cart.forEach(item => {
            html += `
                <div class="cart-item-card">
                    <div class="cart-item-row">
                        <div style="flex:1; min-width:0;">
                            <div class="cart-item-name">${escapeHtml(item.name)}</div>
                            <div class="cart-item-price">
                                ${item.qty} x ${formatRupiah(item.price)}
                            </div>

                            <div class="qty-control">
                                <button type="button" onclick="QrCustomerOrder.changeQty(${item.id}, 'minus')">-</button>
                                <b>${item.qty}</b>
                                <button type="button" onclick="QrCustomerOrder.changeQty(${item.id}, 'plus')">+</button>
                            </div>

                            <input type="text"
                                class="form-control form-control-sm note-input mt-2"
                                placeholder="Catatan item..."
                                value="${escapeHtml(item.note || '')}"
                                oninput="QrCustomerOrder.updateItemNote(${item.id}, this.value)">
                        </div>

                        <div class="cart-line-total">
                            ${formatRupiah(item.qty * item.price)}
                        </div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;

        html += `
            <div class="checkout-form">
            <label>Nama Pemesan</label>
            <input type="text"
                class="form-control mb-2"
                placeholder="Contoh: Gilardo"
                value="${escapeHtml(order.customer_name || '')}"
                oninput="QrCustomerOrder.updateOrderField('customer_name', this.value)">

            <label>No WhatsApp</label>
            <input type="number"
                class="form-control mb-2"
                placeholder="08xxxxxxxxxx"
                value="${escapeHtml(order.customer_phone || '')}"
                oninput="QrCustomerOrder.updateOrderField('customer_phone', this.value)">
            
                <label>Payment Method</label>
                <select class="form-control mb-2"
                    onchange="QrCustomerOrder.updateOrderField('payment_method', this.value)">
                    ${renderPaymentOptions()}
                </select>

                <label>Customer Note</label>
                <textarea class="form-control mb-2"
                    placeholder="Contoh: jangan pedas, tanpa es..."
                    oninput="QrCustomerOrder.updateOrderField('customer_note', this.value)">${escapeHtml(order.customer_note || '')}</textarea>
            </div>

            <div class="summary-box" id="cartSummaryBox"></div>
        `;

        el.innerHTML = html;
        renderCartSummaryOnly();
    }

    function renderPaymentOptions() {
        const entries = Object.entries(paymentOptions);

        if (entries.length === 0) {
            return `<option value="cash">Cash</option>`;
        }

        return entries.map(([value, label]) => {
            return `
                <option value="${escapeHtml(value)}" ${order.payment_method === value ? 'selected' : ''}>
                    ${escapeHtml(label)}
                </option>
            `;
        }).join('');
    }

    function renderCartSummaryOnly() {
        const summary = document.getElementById('cartSummaryBox');
        if (!summary) return;

        const calc = calculate();

        summary.innerHTML = `
            <div class="summary-row">
                <span>Subtotal</span>
                <b>${formatRupiah(calc.subtotal)}</b>
            </div>

            <div class="summary-row">
                <span>Discount</span>
                <b>${formatRupiah(calc.discount)}</b>
            </div>

            ${config.tax_enabled ? `
                <div class="summary-row">
                    <span>Tax ${config.tax_inclusive ? '(Included)' : `(${calc.taxRate}%)`}</span>
                    <b>${formatRupiah(calc.tax)}</b>
                </div>
            ` : ''}

            ${config.service_enabled ? `
                <div class="summary-row">
                    <span>Service ${config.service_inclusive ? '(Included)' : `(${calc.serviceRate}%)`}</span>
                    <b>${formatRupiah(calc.service)}</b>
                </div>
            ` : ''}

            <div class="summary-row grand">
                <span>Total</span>
                <span>${formatRupiah(calc.grand_total)}</span>
            </div>

            <button type="button"
                class="btn-checkout"
                id="checkoutButton"
                onclick="QrCustomerOrder.checkout()"
                ${isCheckoutLoading ? 'disabled' : ''}>
                ${isCheckoutLoading ? 'Memproses...' : 'Checkout Sekarang'}
            </button>
        `;
    }

    function renderOrdersPage() {
        const el = document.getElementById('ordersPageContent');
        const countText = document.getElementById('orderCountText');

        if (countText) {
            countText.innerText = orders.length + ' pesanan';
        }

        if (!el) return;

        if (orders.length === 0) {
            el.innerHTML = `
                <div class="empty-state">
                    <div>
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M7 3h7l5 5v13H7z" stroke-width="1.8"></path>
                                <path d="M14 3v5h5" stroke-width="1.8"></path>
                                <path d="M10 13h6M10 17h6M10 9h2" stroke-width="1.8"></path>
                            </svg>
                        </div>
                        <h5>Belum Ada Pesanan</h5>
                        <p>Pesanan kamu akan muncul di sini</p>
                    </div>
                </div>
            `;
            return;
        }

        let html = `<div class="order-page-list">`;

        orders.slice().reverse().forEach(orderItem => {
            html += `
                <div class="order-item-card">
                    <div class="order-meta">
                        <div>
                            <div class="order-title">
                                Pesanan #${escapeHtml(orderItem.order_code)}
                            </div>
                            <div class="order-date">
                                ${escapeHtml(orderItem.date)}
                            </div>
                        </div>

                        <div class="order-total">
                            ${formatRupiah(orderItem.grand_total)}
                        </div>
                    </div>

                    <hr>

                    ${orderItem.items.map(item => `
                        <div class="order-line">
                            <span>${item.qty}x ${escapeHtml(item.name)}</span>
                            <b>${formatRupiah(item.qty * item.price)}</b>
                        </div>
                    `).join('')}

                    <span class="status-badge">Diproses</span>
                </div>
            `;
        });

        html += `</div>`;
        el.innerHTML = html;
    }

    function filterCategory(category, button) {
        activeCategory = category || 'semua';

        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.classList.remove('active');
        });

        if (button) {
            button.classList.add('active');
        }

        filterMenu();
    }

    function filterMenu() {
        const searchInput = document.getElementById('searchMenu');
        const keyword = (searchInput ? searchInput.value : '').toLowerCase().trim();

        const cards = document.querySelectorAll('.menu-card');
        const noResult = document.getElementById('noMenuResult');
        const menuCountText = document.getElementById('menuCountText');

        let visibleCount = 0;

        cards.forEach(card => {
            const name = (card.getAttribute('data-name') || '').toLowerCase();
            const category = (card.getAttribute('data-category') || '').toLowerCase();

            const matchSearch = name.includes(keyword);
            const matchCategory = activeCategory === 'semua' || category.includes(activeCategory);

            const show = matchSearch && matchCategory;

            card.style.display = show ? 'block' : 'none';

            if (show) {
                visibleCount++;
            }
        });

        if (noResult) {
            noResult.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        if (menuCountText) {
            menuCountText.innerText = visibleCount + ' menu';
        }
    }

    function checkout() {
        if (cart.length === 0) {
            alert('Cart kosong');
            return;
        }

        if (isCheckoutLoading) return;

        isCheckoutLoading = true;
        renderCartSummaryOnly();

        const calc = calculate();
        const payload = {
            table_id: config.tableId,
            customer_name: order.customer_name,
            customer_phone: order.customer_phone,
            items: cart.map(item => ({
                id: item.id,
                qty: item.qty,
                note: item.note || ''
            })),
            payment_method: order.payment_method,
            customer_note: order.customer_note
        };

        // ===== DEBUG LOG =====
        console.log('[QR Order] DEBUG - Config:', {
            checkoutUrl: checkoutUrl,
            csrfToken: csrfToken,
            tableId: config.tableId,
            hasCsrf: !!csrfToken,
            hasUrl: !!checkoutUrl
        });
        console.log('[QR Order] DEBUG - Payload:', payload);
        // =====================

        fetch(checkoutUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            // ===== DEBUG LOG =====
            console.log('[QR Order] DEBUG - Response Status:', response.status);
            console.log('[QR Order] DEBUG - Response Headers:', [...response.headers]);
            const responseText = await response.text();
            console.log('[QR Order] DEBUG - Response Text:', responseText);
            // =====================

            let data = {};
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('[QR Order] DEBUG - Failed to parse JSON:', e);
                throw new Error('Server mengembalikan respons yang tidak valid: ' + responseText.substring(0, 200));
            }

            if (!response.ok) {
                console.error('[QR Order] DEBUG - Checkout failed:', {
                    status: response.status,
                    data: data
                });
                throw new Error(data.message || 'Checkout gagal (status ' + response.status + ')');
            }

            console.log('[QR Order] DEBUG - Checkout success:', data);
            return data;
        })
        .then(res => {
            const newOrder = {
                order_code: res.order_code || (res.data && (res.data.order_code || res.data.id)) || Date.now(),
                date: new Date().toLocaleString('id-ID'),
                items: JSON.parse(JSON.stringify(cart)),
                subtotal: calc.subtotal,
                discount: calc.discount,
                tax: calc.tax,
                service: calc.service,
                grand_total: calc.grand_total,
                payment_method: order.payment_method,
                customer_note: order.customer_note
            };
            orders.push(newOrder);
            saveOrders();

            cart = [];
            saveCart();

            order = {
                customer_name: '',
                customer_phone: '',
                discount: 0,
                payment_method: defaultPaymentMethod,
                customer_note: ''
            };

            alert(res.message || 'Pesanan berhasil dibuat');
            render();
            renderCartPage();
            renderOrdersPage();
            showPage('orders');
        })
        .catch(error => {
            // ===== DEBUG LOG =====
            console.error('[QR Order] DEBUG - Full error:', {
                message: error.message,
                stack: error.stack,
                name: error.name
            });
            // =====================

            alert('Error detail: ' + error.message);
        })
        .finally(() => {
            isCheckoutLoading = false;
            renderCartSummaryOnly();
        });
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    window.QrCustomerOrder = {
        showPage,
        addToCart,
        changeQty,
        updateItemNote,
        updateOrderField,
        filterCategory,
        filterMenu,
        checkout
    };
})();