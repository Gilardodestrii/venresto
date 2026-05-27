window.posCart = {
    items: [],

    add(item) {
        const existing = this.items.find(i => i.id === item.id);

        if (existing) {
            existing.qty += 1;
        } else {
            this.items.push({
                ...item,
                qty: 1,
            });
        }

        return this.items;
    },

    remove(id) {
        this.items = this.items.filter(item => item.id !== id);

        return this.items;
    },

    clear() {
        this.items = [];

        return this.items;
    },

    subtotal() {
        return this.items.reduce((total, item) => {
            return total + (item.price * item.qty);
        }, 0);
    }
};
