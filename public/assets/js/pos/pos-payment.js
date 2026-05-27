window.posPayment = {
    paymentSelect() {
        return document.querySelector('select[name="payment_method"]');
    },

    currentMethod() {
        const select = this.paymentSelect();

        return select ? select.value : null;
    },

    isQrisStatic() {
        return this.currentMethod() === 'qris_static';
    },

    paidAmountInput() {
        return document.querySelector('input[name="paid_amount"]');
    },

    currentPaidAmount() {
        const input = this.paidAmountInput();

        return input ? Number(input.value || 0) : 0;
    },

    currentGrandTotal() {
        const grandTotalText = document.getElementById('grandTotalText');

        if (!grandTotalText) {
            return 0;
        }

        return window.POS?.core?.parseNumber
            ? window.POS.core.parseNumber(grandTotalText.innerText)
            : parseInt(grandTotalText.innerText.replace(/[^0-9]/g, '') || '0');
    },

    bindFormValidation() {
        const form = document.getElementById('posForm');

        if (!form) {
            return;
        }

        let clickedAction = null;

        form.querySelectorAll('button[name="action"]')
            .forEach(button => {
                button.addEventListener('click', () => {
                    clickedAction = button.value;
                });
            });

        form.addEventListener('submit', (event) => {
            const action = clickedAction || event.submitter?.value;
            const cart = window.POS?.cart?.items || [];

            if (!cart.length) {
                event.preventDefault();
                alert('Keranjang masih kosong.');
                return;
            }

            if (action === 'paid') {
                const grandTotal = this.currentGrandTotal();
                const paidAmount = this.currentPaidAmount();

                if (paidAmount <= 0) {
                    const paidInput = this.paidAmountInput();

                    if (paidInput) {
                        paidInput.value = grandTotal;
                    }

                    return;
                }

                if (paidAmount < grandTotal) {
                    event.preventDefault();
                    alert('Nominal pembayaran kurang dari total order.');
                }
            }
        });
    }
};