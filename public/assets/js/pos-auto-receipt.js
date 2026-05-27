window.openReceiptWindow = function (receiptUrl) {
    if (!receiptUrl) {
        return;
    }

    window.open(
        receiptUrl,
        '_blank',
        'width=420,height=720'
    );
};
