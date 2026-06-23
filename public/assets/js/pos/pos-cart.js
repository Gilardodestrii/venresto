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
                    if (other !== input) other.value = value;
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
        return this.items.reduce((total, item) => total + Number(item.price) * Number(item.qty), 0);
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
            if (el.value !== value) el.value = value;
        });
    },

    getDiscount() {
        const input = document.querySelector('#discountInput:not([disabled])');
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
                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                    <div class="font-semibold text-sm">Keranjang kosong</div>
                    <div class="text-xs mt-0.5">Pilih menu untuk mulai order.</div>
                </div>
            `;
        }

        let html = '';
        this.items.forEach((item, index) => {
            const subtotalItem = Number(item.price) * Number(item.qty);
            html += `
                <div class="cart-item flex flex-col gap-2 py-3 border-b border-slate-100 last:border-0">
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-slate-800 leading-tight truncate">${this.escapeHtml(item.name)}</div>
                            <div class="text-xs text-slate-400 mt-0.5">${POS.core.formatRupiah(item.price)} &times; ${item.qty} = <span class="font-semibold text-slate-600">${POS.core.formatRupiah(subtotalItem)}</span></div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button type="button"
                                class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-base flex items-center justify-center transition-colors"
                                onclick="POS.cart.decrease(${index})">&#8722;</button>
                            <span class="w-6 text-center font-bold text-sm">${item.qty}</span>
                            <button type="button"
                                class="w-7 h-7 rounded-lg bg-sky-500 hover:bg-sky-600 text-white font-bold text-base flex items-center justify-center transition-colors"
                                onclick="POS.cart.increase(${index})">&#43;</button>
                        </div>
                    </div>
                    <textarea
                        class="w-full h-10 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-600 placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 resize-none"
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
        const barEl   = document.getElementById('mobileCartBar');

        const totalQty = this.items.reduce((t, item) => t + Number(item.qty), 0);

        if (countEl) countEl.textContent = `${totalQty} Item`;
        if (totalEl) totalEl.textContent = POS.core.formatRupiah(totals.grandTotal);
        if (barEl)   barEl.style.display = this.items.length > 0 ? 'block' : 'none';
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
            el.textContent = POS.core.formatRupiah(totals.subtotal);
        });
        document.querySelectorAll('#serviceText').forEach(el => {
            el.textContent = POS.core.formatRupiah(totals.service);
        });
        document.querySelectorAll('#taxText').forEach(el => {
            el.textContent = POS.core.formatRupiah(totals.tax);
        });
        document.querySelectorAll('#grandTotalText').forEach(el => {
            el.textContent = POS.core.formatRupiah(totals.grandTotal);
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
