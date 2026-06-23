window.posOffline = {
    init() {
        this.renderBadge();
        this.bindEvents();
        this.updateStatus();
    },

    renderBadge() {
        if (document.getElementById('posOfflineBadge')) return;

        const badge = document.createElement('div');
        badge.id = 'posOfflineBadge';
        badge.className = 'fixed bottom-4 right-4 z-50 px-3 py-1 rounded-full text-xs font-semibold shadow-md transition-colors duration-200 bg-emerald-500 text-white';
        badge.textContent = 'Online';
        document.body.appendChild(badge);
    },

    bindEvents() {
        window.addEventListener('online',  () => this.updateStatus());
        window.addEventListener('offline', () => this.updateStatus());
    },

    updateStatus() {
        const badge = document.getElementById('posOfflineBadge');
        if (!badge) return;

        if (navigator.onLine) {
            badge.textContent = 'Online';
            badge.classList.remove('bg-red-500');
            badge.classList.add('bg-emerald-500');
        } else {
            badge.textContent = 'Offline';
            badge.classList.remove('bg-emerald-500');
            badge.classList.add('bg-red-500');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.posOffline.init();
});
