window.posCart = {

    items: [],

    bindAddToCart() {

        document.querySelectorAll('.addToCart')
            .forEach(btn => {

                btn.addEventListener('click', () => {

                    this.add({
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        price: btn.dataset.price,
                        qty: 1,
                        note: '',
                    });
                });
            });
    },

    bindDiscountInput() {
        const discountInput = document.getElementById('discountInput');

        if (!discountInput) {
            return;
        }

        discountInput.addEventListener('input', () => {
            this.render();
        });
    },

    add(item) {

        const existing = this.items.find(i => i.id === item.id);

        if (existing) {
            existing.qty += 1;
        } else {
            this.items.push({
                ...item,
                qty: 1,
                note: item.note || '',
            });
        }

        this.render();

        return this.items;
    },

    remove(id) {

        this.items = this.items.filter(item => item.id !== id);

        this.render();

        return this.items;
    },

    clear() {

        this.items = [];

        this.render();

        return this.items;
    },

    subtotal() {

        return this.items.reduce((total, item) => {
            return total + (item.price * item.qty);
        }, 0);
    },

    increase(index) {
        this.items[index].qty++;
        this.render();
    },

    decrease(index) {

        if (this.items[index].qty > 1) {
            this.items[index].qty--;
        } else {
            this.items.splice(index, 1);
        }

        this.render();
    },

    note(index, value) {
        this.items[index].note = value;
    },

    render() {

        const container = document.getElementById('cartContainer');

        if (!container) {
            return;
        }

        let html = '';

        const discount = parseFloat(
            document.getElementById('discountInput')?.value || 0
        );

        const totals = POS.core.calculateTotals(
            this.items,
            window.posSettings || {},
            discount
        );

        this.items.forEach((item, index) => {

            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold">${item.name}</div>
                            <small class="text-secondary">
                                ${POS.core.formatRupiah(item.price)}
                            </small>
                        </div>

                        <div class="qty-box">
                            <button type="button"
                                class="qty-btn"
                                onclick="POS.cart.decrease(${index})">
                                -
                            </button>

                            <strong>${item.qty}</strong>

                            <button type="button"
                                class="qty-btn"
                                onclick="POS.cart.increase(${index})">
                                +
                            </button>
                        </div>
                    </div>

                    <textarea class="form-control mt-3"
                        placeholder="Catatan item..."
                        oninput="POS.cart.note(${index}, this.value)">${item.note ?? ''}</textarea>

                    <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                    <input type="hidden" name="items[${index}][price]" value="${item.price}">
                    <input type="hidden" name="items[${index}][note]" value="${item.note ? item.note : ''}">
                </div>
            `;
        });

        container.innerHTML = html;

        const subtotalText = document.getElementById('subtotalText');
        const serviceText = document.getElementById('serviceText');
        const taxText = document.getElementById('taxText');
        const grandTotalText = document.getElementById('grandTotalText');

        if (subtotalText) {
            subtotalText.innerHTML = POS.core.formatRupiah(totals.subtotal);
        }

        if (serviceText) {
            serviceText.innerHTML = POS.core.formatRupiah(totals.service);
        }

        if (taxText) {
            taxText.innerHTML = POS.core.formatRupiah(totals.tax);
        }

        if (grandTotalText) {
            grandTotalText.innerHTML = POS.core.formatRupiah(totals.grandTotal);
        }

        const serviceInput = document.getElementById('serviceInput');
        const taxInput = document.getElementById('taxInput');

        if (serviceInput) {
            serviceInput.value = totals.service;
        }

        if (taxInput) {
            taxInput.value = totals.tax;
        }
    }
};