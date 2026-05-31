window.posCart = {
    items: [],

    bindAddToCart() {
        document.querySelectorAll('.addToCart').forEach(btn => {
            btn.addEventListener('click', () => {
                this.add({
                    id: String(btn.dataset.id),
                    name: btn.dataset.name,
                    price: Number(btn.dataset.price),
                    qty: 1,
                    note: '',
                });
            });
        });
    },

    bindDiscountInput() {
        document.querySelectorAll('#discountInput').forEach(input => {
            input.addEventListener('input', () => {
                const value = input.value;

                document.querySelectorAll('#discountInput').forEach(other => {
                    if (other !== input) {
                        other.value = value;
                    }
                });

                this.render();
            });
        });
    },

    add(item) {
        const existing = this.items.find(i => String(i.id) === String(item.id));

        if (existing) {
            existing.qty += 1;
        } else {
            this.items.push({
                ...item,
                price: Number(item.price),
                qty: 1,
                note: item.note || '',
            });
        }

        this.render();

        return this.items;
    },

    remove(id) {
        this.items = this.items.filter(item => String(item.id) !== String(id));
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
            return total + Number(item.price) * Number(item.qty);
        }, 0);
    },

    increase(index) {
        if (!this.items[index]) return;

        this.items[index].qty++;
        this.render();
    },

    decrease(index) {
        if (!this.items[index]) return;

        if (this.items[index].qty > 1) {
            this.items[index].qty--;
        } else {
            this.items.splice(index, 1);
        }

        this.render();
    },

    note(index, value) {
        if (!this.items[index]) return;

        this.items[index].note = value;

        document.querySelectorAll(`[data-cart-note-index="${index}"]`).forEach(el => {
            if (el.value !== value) {
                el.value = value;
            }
        });
    },

    getDiscount() {
        const input = document.querySelector('#discountInput');
        return parseFloat(input?.value || 0);
    },

    syncDiscount(value) {
        document.querySelectorAll('#discountInput').forEach(input => {
            input.value = value;
        });
    },

    renderCartHtml() {
        if (this.items.length === 0) {
            return `
                <div class="text-center text-muted py-4">
                    <div style="font-size:34px;">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <div class="fw-semibold mt-2">Keranjang kosong</div>
                    <small>Pilih menu untuk mulai order.</small>
                </div>
            `;
        }

        let html = '';

        this.items.forEach((item, index) => {
            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between gap-2">
                        <div>
                            <div class="fw-bold">${this.escapeHtml(item.name)}</div>
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
                        data-cart-note-index="${index}"
                        oninput="POS.cart.note(${index}, this.value)">${this.escapeHtml(item.note ?? '')}</textarea>

                    <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                    <input type="hidden" name="items[${index}][price]" value="${item.price}">
                    <input type="hidden" name="items[${index}][note]" value="${this.escapeHtml(item.note ?? '')}">
                </div>
            `;
        });

        return html;
    },

    renderMobileBar(totals) {
        const countEl = document.getElementById('mobileCartCount');
        const totalEl = document.getElementById('mobileCartTotal');
        const barEl = document.getElementById('mobileCartBar');

        const totalQty = this.items.reduce((total, item) => {
            return total + Number(item.qty);
        }, 0);

        if (countEl) {
            countEl.innerHTML = `${totalQty} Item`;
        }

        if (totalEl) {
            totalEl.innerHTML = POS.core.formatRupiah(totals.grandTotal);
        }

        if (barEl) {
            barEl.style.display = this.items.length > 0 ? 'block' : 'none';
        }
    },

    render() {
        const discount = this.getDiscount();

        this.syncDiscount(discount);

        const totals = POS.core.calculateTotals(
            this.items,
            window.posSettings || {},
            discount
        );

        const html = this.renderCartHtml();

        document.querySelectorAll('#cartContainer').forEach(container => {
            container.innerHTML = html;
        });

        document.querySelectorAll('#subtotalText').forEach(el => {
            el.innerHTML = POS.core.formatRupiah(totals.subtotal);
        });

        document.querySelectorAll('#serviceText').forEach(el => {
            el.innerHTML = POS.core.formatRupiah(totals.service);
        });

        document.querySelectorAll('#taxText').forEach(el => {
            el.innerHTML = POS.core.formatRupiah(totals.tax);
        });

        document.querySelectorAll('#grandTotalText').forEach(el => {
            el.innerHTML = POS.core.formatRupiah(totals.grandTotal);
        });

        document.querySelectorAll('#serviceInput').forEach(el => {
            el.value = totals.service;
        });

        document.querySelectorAll('#taxInput').forEach(el => {
            el.value = totals.tax;
        });

        this.renderMobileBar(totals);
    },

    escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
};