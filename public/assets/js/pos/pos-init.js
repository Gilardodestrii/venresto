document.addEventListener('DOMContentLoaded', function () {
    window.POS = {
        core: window.posCore || {},
        cart: window.posCart || {},
        payment: window.posPayment || {},
        receipt: window.posReceipt || {},
        ui: window.posUi || {},
    };

    console.log('VenResto POS initialized', window.POS);
});
