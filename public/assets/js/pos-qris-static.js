document.addEventListener('DOMContentLoaded', function () {
    initQrisStaticButtons();

    document.addEventListener('change', function (event) {
        if (event.target.matches('select[name="payment_method"]')) {
            syncQrisStaticBoxes();
        }
    });

    const mobileDrawer = document.getElementById('mobileCartDrawer');

    if (mobileDrawer) {
        mobileDrawer.addEventListener('shown.bs.offcanvas', function () {
            syncQrisStaticBoxes();
        });
    }

    window.addEventListener('resize', function () {
        syncQrisStaticBoxes();
    });

    syncQrisStaticBoxes();
});

function initQrisStaticButtons()
{
    document.querySelectorAll('select[name="payment_method"]').forEach(function (paymentSelect, index) {
        const parent = paymentSelect.parentNode;

        if (!parent) {
            return;
        }

        if (parent.querySelector('.qris-static-box')) {
            return;
        }

        const qrisBox = document.createElement('div');
        qrisBox.className = 'qris-static-box mt-3 d-none';

        qrisBox.innerHTML = `
            <button type="button"
                    class="btn btn-outline-primary rounded-4 w-100 generate-qris-button"
                    data-qris-index="${index}">
                <i class="bi bi-qr-code me-1"></i>
                Generate QRIS Nominal
            </button>
        `;

        parent.appendChild(qrisBox);
    });

    document.querySelectorAll('.generate-qris-button').forEach(function (button) {
        button.addEventListener('click', function () {
            generateQrisStatic();
        });
    });
}

function getActiveCartRoot()
{
    const isMobile = window.innerWidth < 992;

    if (isMobile) {
        return document.getElementById('mobileCartDrawer');
    }

    return document.querySelector('.pos-cart-desktop');
}

function getActivePaymentSelect()
{
    const root = getActiveCartRoot();

    if (!root) {
        return null;
    }

    return root.querySelector('select[name="payment_method"]:not(:disabled)');
}

function syncQrisStaticBoxes()
{
    document.querySelectorAll('.qris-static-box').forEach(function (box) {
        box.classList.add('d-none');
    });

    const activeSelect = getActivePaymentSelect();

    if (!activeSelect) {
        return;
    }

    const wrapper = activeSelect.parentNode;

    if (!wrapper) {
        return;
    }

    const activeBox = wrapper.querySelector('.qris-static-box');

    if (!activeBox) {
        return;
    }

    if (activeSelect.value === 'qris_static') {
        activeBox.classList.remove('d-none');
    } else {
        activeBox.classList.add('d-none');
    }
}

function getGrandTotalFromUI()
{
    const root = getActiveCartRoot();

    if (!root) {
        return 0;
    }

    const grandTotalEl = root.querySelector('#grandTotalText');

    if (!grandTotalEl) {
        return 0;
    }

    return parseInt(
        grandTotalEl.innerText.replace(/[^0-9]/g, '') || '0'
    );
}

function formatRupiahQris(number)
{
    return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
}

async function generateQrisStatic()
{
    const amount = getGrandTotalFromUI();

    if (!amount || amount <= 0) {
        alert('Grand total belum valid.');
        return;
    }

    const modalEl = document.getElementById('qrisStaticModal');

    if (!modalEl) {
        alert('QRIS modal tidak ditemukan.');
        return;
    }

    if (!window.qrisStaticGenerateUrl) {
        alert('URL generate QRIS belum tersedia.');
        return;
    }

    if (!window.csrfToken) {
        alert('CSRF token belum tersedia.');
        return;
    }

    const modal = new bootstrap.Modal(modalEl);

    const amountText = document.getElementById('qrisAmountText');
    const loading = document.getElementById('qrisLoading');
    const result = document.getElementById('qrisResult');
    const errorBox = document.getElementById('qrisError');
    const qrisImage = document.getElementById('qrisImage');
    const payloadText = document.getElementById('qrisPayloadText');

    if (amountText) {
        amountText.innerText = formatRupiahQris(amount);
    }

    if (loading) {
        loading.classList.remove('d-none');
    }

    if (result) {
        result.classList.add('d-none');
    }

    if (errorBox) {
        errorBox.classList.add('d-none');
        errorBox.innerText = '';
    }

    modal.show();

    try {
        const response = await fetch(window.qrisStaticGenerateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
            },
            body: JSON.stringify({
                amount: amount
            })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Gagal generate QRIS.');
        }

        if (qrisImage) {
            qrisImage.src = data.qr_url;
        }

        if (payloadText) {
            payloadText.value = data.payload;
        }

        if (result) {
            result.classList.remove('d-none');
        }

    } catch (error) {
        if (errorBox) {
            errorBox.innerText = error.message;
            errorBox.classList.remove('d-none');
        } else {
            alert(error.message);
        }

    } finally {
        if (loading) {
            loading.classList.add('d-none');
        }
    }
}