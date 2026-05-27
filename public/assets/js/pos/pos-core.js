window.posCore = {
    formatRupiah(number = 0) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    },

    parseNumber(value = '') {
        return parseInt(String(value).replace(/[^0-9]/g, '')) || 0;
    },

    calculateTotals(cart = [], settings = {}, discount = 0) {

        let subtotal = 0;

        cart.forEach(item => {
            subtotal += Number(item.qty) * Number(item.price);
        });

        let baseAmount = Math.max(0, subtotal - discount);

        let service = 0;

        if (settings.service_enabled && !settings.service_inclusive) {
            service = Math.round(baseAmount * settings.service_rate);
        }

        let taxBase = baseAmount + service;

        let tax = 0;

        if (settings.tax_enabled && !settings.tax_inclusive) {
            tax = Math.round(taxBase * settings.tax_rate);
        }

        let grandTotal = Math.max(0, baseAmount + service + tax);

        return {
            subtotal,
            service,
            tax,
            grandTotal,
        };
    }
};