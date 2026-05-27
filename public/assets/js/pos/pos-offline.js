window.posOffline = {
    init() {
        this.renderBadge();
        this.bindEvents();
        this.updateStatus();
    },

    renderBadge() {
        if (document.getElementById('posOfflineBadge')) {
            return;
        }

        const badge = document.createElement('div');
        badge.id = 'posOfflineBadge';
        badge.className = 'position-fixed bottom-0 end-0 m-3 badge rounded-pill bg-success shadow-sm';
        badge.style.zIndex = '9999';
        badge.innerText = 'Online';

        document.body.appendChild(badge);
    },

    bindEvents() {
        window.addEventListener('online', () => this.updateStatus());
        window.addEventListener('offline', () => this.updateStatus());
    },

    updateStatus() {
        const badge = document.getElementById('posOfflineBadge');

        if (!badge) {
            return;
        }

        if (navigator.onLine) {
            badge.innerText = 'Online';
            badge.classList.remove('bg-danger');
            badge.classList.add('bg-success');
        } else {
            badge.innerText = 'Offline';
            badge.classList.remove('bg-success');
            badge.classList.add('bg-danger');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.posOffline.init();
});
