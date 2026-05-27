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
                const activeModal = document.querySelector('.modal.show');

                if (activeModal) {
                    const modalInstance = bootstrap.Modal.getInstance(activeModal);

                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.posShortcut.init();
});
