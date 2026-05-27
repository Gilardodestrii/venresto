window.posCore = {
    formatRupiah(number = 0) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    },

    parseNumber(value = '') {
        return parseInt(String(value).replace(/[^0-9]/g, '')) || 0;
    }
};
