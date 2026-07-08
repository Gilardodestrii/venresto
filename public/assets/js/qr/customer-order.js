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
    let pollingInterval;

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
        startOrderPolling();
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

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    function bindSearchInput() {
        const searchInput = document.getElementById('searchMenu');

        if (searchInput) {
            searchInput.addEventListener('input', debounce(filterMenu, 300));
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

        if (pageMenu) pageMenu.classList.add('active');
        if (pageCart) pageCart.classList.add('active');
        if (pageOrders) pageOrders.classList.add('active');

        if (navMenu) navMenu.classList.add('active');
        if (navCart) navCart.classList.add('active');
        if (navOrders) navOrders.classList.add('active');
    }

    function showSuccessPopup(order) {
        // Buat elemen popup
        const popup = document.createElement('div');
        popup.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

        popup.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 text-center shadow-xl">
                <div class="mx-auto mb-4 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Pesanan Berhasil Dibuat!</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Kode Pesanan: <strong>${order.order_code}</strong><br>
                    Total: <strong>Rp ${order.grand_total.toLocaleString('id-ID')}</strong>
                </p>
                <div class="mt-4">
                    <button id="closePopup" class="inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                        Lihat Pesanan
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(popup);

        // Event untuk menutup popup dan pindah ke halaman pesanan
        document.getElementById('closePopup').addEventListener('click', () => {
            popup.remove();
            showPage('orders');
        });

        // Auto-close setelah 5 detik
        setTimeout(() => {
            if (popup.parentNode) {
                popup.remove();
                showPage('orders');
            }
        }, 5000);
    }

    function startOrderPolling() {
        if (pollingInterval) clearInterval(pollingInterval);

        pollingInterval = setInterval(() => {
            fetchOrderStatus();
        }, 5000); // Poll setiap 5 detik
    }

    function fetchOrderStatus() {
        if (!config.tenantSlug || !config.outletId) return;

        fetch(`/api/orders/${config.tenantSlug}/${config.outletId}/status`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateOrdersFromApi(data.data);
            }
        })
        .catch(error => {
            console.error('[QR Order] Error fetching order status:', error);
        });
    }

    function updateOrdersFromApi(apiOrders) {
        // Update status order lokal dengan data dari API
        orders.forEach(order => {
            const apiOrder = apiOrders.find(o => o.code === order.order_code);
            if (apiOrder) {
                order.status = apiOrder.status;
            }
        });

        // Render ulang halaman pesanan
        if (document.getElementById('pageOrders')?.classList.contains('active')) {
            renderOrdersPage();
        }
    }

    function getStatusBadge(status) {
        const statusMap = {
            'new': { text: 'Baru', class: 'bg-blue-100 text-blue-800' },
            'open': { text: 'Diproses', class: 'bg-yellow-100 text-yellow-800' },
            'processing': { text: 'Dimasak', class: 'bg-orange-100 text-orange-800' },
            'cooking': { text: 'Sedang Dimasak', class: 'bg-red-100 text-red-800' },
            'ready': { text: 'Siap Diantar', class: 'bg-green-100 text-green-800' },
            'completed': { text: 'Selesai', class: 'bg-gray-100 text-gray-800' },
        };

        return statusMap[status] || { text: status, class: 'bg-gray-100 text-gray-800' };
    }

    function renderOrdersPage() {
        const ordersPage = document.getElementById('pageOrders');
        if (!ordersPage) return;

        let html = `
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Daftar Pesanan</h2>
                <div class="space-y-4">
        `;

        if (orders.length === 0) {
            html += `
                <div class="text-center py-8">
                    <p class="text-gray-500">Belum ada pesanan</p>
                </div>
            `;
        } else {
            orders.forEach(order => {
                const statusBadge = getStatusBadge(order.status);
                html += `
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold">Pesanan #${order.order_code}</h3>
                                <p class="text-sm text-gray-500">${order.date}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${statusBadge.class}">
                                ${statusBadge.text}
                            </span>
                        </div>
                        <div class="mt-2 border-t pt-2">
                            <div class="space-y-1">
                `;

                if (order.items && order.items.length > 0) {
                    order.items.forEach(item => {
                        html += `
                            <div class="flex justify-between">
                                <span>${item.qty}x ${item.name}</span>
                                <span>Rp ${(item.price * item.qty).toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    });
                }

                html += `
                            </div>
                            <div class="mt-2 pt-2 border-t flex justify-between font-bold">
                                <span>Total</span>
                                <span>Rp ${order.grand_total.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>
            </div>
        `;

        ordersPage.innerHTML = html;
    }

    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                qty: 1,
                note: ''
            });
        }

        saveCart();
        render();
        renderCartPage();
        showPage('cart');
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        render();
        renderCartPage();
    }

    function updateCartQty(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                removeFromCart(id);
            } else {
                saveCart();
                render();
                renderCartPage();
            }
        }
    }

    function setCartNote(id, note) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.note = note;
            saveCart();
        }
    }

    function filterCategory(category, button) {
        activeCategory = category;

        document.querySelectorAll('.category-chip').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-800');
        });

        if (button) {
            button.classList.remove('bg-gray-200', 'text-gray-800');
            button.classList.add('bg-blue-600', 'text-white');
        }

        filterMenu();
    }

    function filterMenu() {
        const searchInput = document.getElementById('searchMenu');
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

        document.querySelectorAll('.menu-item').forEach(item => {
            const category = item.dataset.category || 'semua';
            const name = item.dataset.name ? item.dataset.name.toLowerCase() : '';

            const categoryMatch = activeCategory === 'semua' || category === activeCategory;
            const searchMatch = !searchTerm || name.includes(searchTerm);

            if (categoryMatch && searchMatch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function calculate() {
        let subtotal = 0;
        let totalItems = 0;

        cart.forEach(item => {
            subtotal += item.price * item.qty;
            totalItems += item.qty;
        });

        const tax = config.tax_enabled ? Math.round(subtotal * (config.tax_rate / 100)) : 0;
        const service = config.service_enabled ? Math.round(subtotal * (config.service_rate / 100)) : 0;
        const discount = 0; // Default, bisa disesuaikan
        const afterDiscount = subtotal - discount;

        const grandTotal = afterDiscount + tax + service;

        return {
            subtotal: subtotal,
            discount: discount,
            tax: tax,
            service: service,
            grand_total: grandTotal,
            total_items: totalItems
        };
    }

    function render() {
        const calc = calculate();

        // Update cart badge
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge) {
            cartBadge.textContent = calc.total_items;
            cartBadge.classList.remove('hidden');
            if (calc.total_items === 0) {
                cartBadge.classList.add('hidden');
            }
        }

        // Update cart summary
        renderCartSummaryOnly();
    }

    function renderCartSummaryOnly() {
        const calc = calculate();
        const cartSummary = document.getElementById('cartSummary');

        if (cartSummary) {
            cartSummary.innerHTML = `
                <div class="text-sm text-gray-500">Subtotal: Rp ${calc.subtotal.toLocaleString('id-ID')}</div>
                ${calc.discount > 0 ? `<div class="text-sm text-gray-500">Diskon: -Rp ${calc.discount.toLocaleString('id-ID')}</div>` : ''}
                ${config.tax_enabled ? `<div class="text-sm text-gray-500">Pajak (${config.tax_rate}%): Rp ${calc.tax.toLocaleString('id-ID')}</div>` : ''}
                ${config.service_enabled ? `<div class="text-sm text-gray-500">Layanan (${config.service_rate}%): Rp ${calc.service.toLocaleString('id-ID')}</div>` : ''}
                <div class="text-lg font-bold">Total: Rp ${calc.grand_total.toLocaleString('id-ID')}</div>
            `;
        }
    }

    function renderCartPage() {
        const cartPage = document.getElementById('pageCart');
        if (!cartPage) return;

        const calc = calculate();

        let html = `
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>
                <div id="cartItems" class="space-y-3 mb-4">
        `;

        if (cart.length === 0) {
            html += `
                <div class="text-center py-8">
                    <p class="text-gray-500">Keranjang kosong</p>
                    <button onclick="showPage('menu')" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Kembali ke Menu
                    </button>
                </div>
            `;
        } else {
            cart.forEach(item => {
                html += `
                    <div class="bg-white rounded-lg shadow p-3 flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium">${item.name}</h4>
                            <p class="text-sm text-gray-500">Rp ${item.price.toLocaleString('id-ID')} x ${item.qty}</p>
                            <div class="flex items-center mt-1">
                                <button onclick="updateCartQty(${item.id}, -1)" class="px-2 py-0.5 border rounded text-sm">-</button>
                                <span class="px-2">${item.qty}</span>
                                <button onclick="updateCartQty(${item.id}, 1)" class="px-2 py-0.5 border rounded text-sm">+</button>
                            </div>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                `;
            });

            html += `
                </div>
                <div id="cartSummary" class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="text-sm text-gray-500">Subtotal: Rp ${calc.subtotal.toLocaleString('id-ID')}</div>
                    ${calc.discount > 0 ? `<div class="text-sm text-gray-500">Diskon: -Rp ${calc.discount.toLocaleString('id-ID')}</div>` : ''}
                    ${config.tax_enabled ? `<div class="text-sm text-gray-500">Pajak (${config.tax_rate}%): Rp ${calc.tax.toLocaleString('id-ID')}</div>` : ''}
                    ${config.service_enabled ? `<div class="text-sm text-gray-500">Layanan (${config.service_rate}%): Rp ${calc.service.toLocaleString('id-ID')}</div>` : ''}
                    <div class="text-lg font-bold mt-2">Total: Rp ${calc.grand_total.toLocaleString('id-ID')}</div>
                </div>

                <div class="space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" id="customerName" value="${order.customer_name}" 
                               onchange="order.customer_name = this.value"
                               class="w-full px-3 py-2 border rounded-lg mt-1" placeholder="Nama">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="tel" id="customerPhone" value="${order.customer_phone}" 
                               onchange="order.customer_phone = this.value"
                               class="w-full px-3 py-2 border rounded-lg mt-1" placeholder="08xxxx">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea id="customerNote" onchange="order.customer_note = this.value"
                                  class="w-full px-3 py-2 border rounded-lg mt-1" placeholder="Catatan pesanan...">${order.customer_note}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select id="paymentMethod" onchange="order.payment_method = this.value"
                                class="w-full px-3 py-2 border rounded-lg mt-1">
                            ${Object.entries(paymentOptions).map(([key, label]) => `
                                <option value="${key}" ${order.payment_method === key ? 'selected' : ''}>${label}</option>
                            `).join('')}
                        </select>
                    </div>
                    <button onclick="checkout()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition-colors">
                        Checkout
                    </button>
                </div>
            `;
        }

        cartPage.innerHTML = html;
    }

    function renderMenu() {
        const menuPage = document.getElementById('pageMenu');
        if (!menuPage) return;

        // Menu items akan di-render dari HTML yang sudah ada
        // Kita hanya perlu update visibility berdasarkan filter
        filterMenu();
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
                customer_note: order.customer_note,
                status: 'new'
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

            // Tampilkan popup sukses
            showSuccessPopup(newOrder);

            render();
            renderCartPage();
            renderOrdersPage();
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
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    // Make functions globally available
    window.addToCart = addToCart;
    window.removeFromCart = removeFromCart;
    window.updateCartQty = updateCartQty;
    window.setCartNote = setCartNote;
    window.showPage = showPage;
    window.checkout = checkout;
    window.filterCategory = filterCategory;
    window.render = render;
    window.renderCartPage = renderCartPage;
    window.renderOrdersPage = renderOrdersPage;
    window.renderMenu = renderMenu;
})();
