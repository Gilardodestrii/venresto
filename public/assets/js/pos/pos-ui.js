window.posUi = {
    toggle(el, show = true) {
        if (!el) {
            return;
        }

        el.classList.toggle('hidden', !show);
    },

    qs(selector) {
        return document.querySelector(selector);
    },

    qsa(selector) {
        return document.querySelectorAll(selector);
    },

    bindSearchMenu() {

        const search = document.getElementById('searchMenu');

        if (!search) {
            return;
        }

        search.addEventListener('keyup', function () {

            let keyword = this.value.toLowerCase();

            document.querySelectorAll('.menu-filter')
                .forEach(item => {

                    let name = item.dataset.name;

                    item.style.display =
                        name.includes(keyword)
                            ? 'block'
                            : 'none';
                });
        });
    },

    bindCategoryFilter() {

        document.querySelectorAll('.category-btn')
            .forEach(btn => {

                btn.addEventListener('click', function () {

                    document.querySelectorAll('.category-btn')
                        .forEach(x => x.classList.remove('active'));

                    this.classList.add('active');

                    let category = this.dataset.category;

                    document.querySelectorAll('.menu-filter')
                        .forEach(item => {

                            item.style.display =
                                category == 'all' ||
                                item.dataset.category == category
                                    ? 'block'
                                    : 'none';
                        });
                });
            });
    }
};