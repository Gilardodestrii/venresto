<div class="modal fade" id="qrisStaticModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">QRIS Static Nominal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <div class="text-muted mb-2">Nominal Pembayaran</div>
                <h4 class="fw-bold mb-3" id="qrisAmountText">Rp 0</h4>

                <div id="qrisLoading" class="py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Generate QRIS...</div>
                </div>

                <div id="qrisResult" class="d-none">
                    <img id="qrisImage" src="" alt="QRIS" class="img-fluid rounded-4 border p-2 mb-3" style="max-width:320px;">
                    <textarea id="qrisPayloadText" class="form-control small" rows="4" readonly></textarea>
                </div>

                <div id="qrisError" class="alert alert-danger d-none mt-3"></div>
            </div>
        </div>
    </div>
</div>
