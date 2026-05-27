window.posReceipt = {
    open(receiptUrl) {
        if (!receiptUrl) {
            return;
        }

        window.open(
            receiptUrl,
            '_blank',
            'width=420,height=720'
        );
    },

    autoOpen(receiptUrl) {
        if (!receiptUrl) {
            return;
        }

        setTimeout(() => {
            this.open(receiptUrl);
        }, 600);
    }
};
