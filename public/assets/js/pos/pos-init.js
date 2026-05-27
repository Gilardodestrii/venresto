document.addEventListener('DOMContentLoaded', function () {
    window.POS = {
        core: window.posCore || {},
        cart: window.posCart || {},
        payment: window.posPayment || {},
        receipt: window.posReceipt || {},
        ui: window.posUi || {},
    };

    if (window.POS.cart?.bindAddToCart) {
        window.POS.cart.bindAddToCart();
    }

    if (window.POS.cart?.bindDiscountInput) {
        window.POS.cart.bindDiscountInput();
    }

    if (window.POS.ui?.bindSearchMenu) {
        window.POS.ui.bindSearchMenu();
    }

    if (window.POS.ui?.bindCategoryFilter) {
        window.POS.ui.bindCategoryFilter();
    }

    if (window.POS.payment?.bindFormValidation) {
        window.POS.payment.bindFormValidation();
    }

    console.log('VenResto POS initialized', window.POS);
});