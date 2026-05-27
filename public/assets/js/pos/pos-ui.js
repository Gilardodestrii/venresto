window.posUi = {
    toggle(el, show = true) {
        if (!el) {
            return;
        }

        el.classList.toggle('d-none', !show);
    },

    qs(selector) {
        return document.querySelector(selector);
    },

    qsa(selector) {
        return document.querySelectorAll(selector);
    }
};
