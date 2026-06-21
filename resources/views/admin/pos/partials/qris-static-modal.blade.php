<div x-data="{ show: false }" x-show="show" x-on:open-modal.window="show = true" x-on:close-modal.window="show = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;" x-show="show">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h5 class="text-lg font-bold text-gray-900">QRIS Static Nominal</h5>
            <button type="button" x-on:click="$dispatch('close-modal')" class="p-1 rounded-full hover:bg-gray-100 transition-colors" aria-label="Close">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6 text-center">
            <div class="text-gray-500 mb-2">Nominal Pembayaran</div>
            <h4 class="text-xl font-bold text-gray-900 mb-3" id="qrisAmountText">Rp 0</h4>

            <div id="qrisLoading" class="hidden py-12">
                <div class="inline-block w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <div class="mt-2 text-gray-500 text-sm">Generate QRIS...</div>
            </div>

            <div id="qrisResult" class="hidden">
                <img id="qrisImage" src="" alt="QRIS" class="max-w-full rounded-2xl border border-gray-200 p-2 mb-3 mx-auto">
                <textarea id="qrisPayloadText" class="w-full px-3 py-2 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg resize-none" rows="4" readonly></textarea>
            </div>

            <div id="qrisError" class="hidden mt-3 px-4 py-3 text-sm text-red-700 bg-red-100 rounded-lg"></div>
        </div>
    </div>
</div>
