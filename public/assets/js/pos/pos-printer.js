window.posPrinter = {
    printReceipt(url) {
        if (!url) {
            return;
        }

        window.open(
            url,
            '_blank',
            'width=420,height=720'
        );
    },

    printElement(elementId) {
        const element = document.getElementById(elementId);

        if (!element) {
            return;
        }

        const printWindow = window.open('', '_blank');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print</title>
                </head>
                <body>
                    ${element.innerHTML}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
        }, 400);
    }
};
