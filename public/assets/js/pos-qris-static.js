document.addEventListener('DOMContentLoaded', function () {
    const paymentSelect = document.querySelector('select[name="payment_method"]');

    if (!paymentSelect) {
        return;
    }

    const qrisBox = document.createElement('div');
    qrisBox.id = 'qrisStaticBox';
    qrisBox.className = 'mt-3 d-none';

    qrisBox.innerHTML = `
        <button type="button" class="btn btn-outline-primary rounded-4 w-100" id="generateQrisButton">
            <i class="bi bi-qr-code me-1"></i>
            Generate QRIS Nominal
        </button>
    `;

    paymentSelect.parentNode.appendChild(qrisBox);

    function toggleQrisBox() {
        qrisBox.classList.toggle('d-none', paymentSelect.value !== 'qris_static');
    }

    paymentSelect.addEventListener('change', toggleQrisBox);
    toggleQrisBox();

    document.getElementById('generateQrisButton').addEventListener('click', generateQrisStatic);
});

function getGrandTotalFromUI()
{
    const grandTotalEl = document.getElementById('grandTotalText');

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

    const modal = new bootstrap.Modal(modalEl);

    document.getElementById('qrisAmountText').innerText = formatRupiahQris(amount);
    document.getElementById('qrisLoading').classList.remove('d-none');
    document.getElementById('qrisResult').classList.add('d-none');
    document.getElementById('qrisError').classList.add('d-none');

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

        document.getElementById('qrisImage').src = data.qr_url;
        document.getElementById('qrisPayloadText').value = data.payload;
        document.getElementById('qrisResult').classList.remove('d-none');

    } catch (error) {
        document.getElementById('qrisError').innerText = error.message;
        document.getElementById('qrisError').classList.remove('d-none');

    } finally {
        document.getElementById('qrisLoading').classList.add('d-none');
    }
}
