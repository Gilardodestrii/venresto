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
    }
};
