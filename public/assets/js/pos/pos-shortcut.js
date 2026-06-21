window.posShortcut = {
    init() {
        document.addEventListener('keydown', (event) => {
            if (event.key === 'F9') {
                event.preventDefault();

                const submitButton = document.querySelector('[data-pos-submit]');

                if (submitButton) {
                    submitButton.click();
                }
            }

            if (event.key === 'Escape') {
                const activeModal = document.querySelector('.modal:not(.hidden)');

                if (activeModal) {
                    activeModal.classList.add('hidden');
                }
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.posShortcut.init();
});
