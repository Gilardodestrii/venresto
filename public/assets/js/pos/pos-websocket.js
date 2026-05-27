window.posWebsocket = {
    connected: false,

    init() {
        console.log('POS websocket module initialized');

        this.connected = true;
    },

    notify(channel, payload = {}) {
        console.log('[POS WS]', channel, payload);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.posWebsocket.init();
});
