const cartLocks = {};

document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', (e) => {

        /* ===============================
           ADD TO CART
        =============================== */
        const addBtn = e.target.closest('.add-to-cart');
        if (addBtn) {
            const skuId = addBtn.dataset.skuId;

            // 🔒 LOCK
            if (cartLocks[skuId]) return;
            cartLocks[skuId] = true;

            const unit = addBtn.dataset.unit;
            const step = unit === 'kg' ? 0.5 : 1;
            
            addBtn.classList.add('cart-loading');

            fetch(`/cart/add/${skuId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: step })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                addBtn.classList.add('d-none');

                const controls = addBtn.nextElementSibling;
                controls.classList.remove('d-none');
                controls.querySelector('.cart-qty').innerText = step;

                document.getElementById('cart-count').innerText = data.cart_count;
            })
            .finally(() => {
                cartLocks[skuId] = false;
                addBtn.classList.remove('cart-loading');
            });
        }

        /* ===============================
           PLUS / MINUS
        =============================== */
        const plusBtn =
            e.target.closest('.cart-plus') ||
            e.target.closest('.basket-plus');

        const minusBtn =
            e.target.closest('.cart-minus') ||
            e.target.closest('.basket-minus');


        if (plusBtn || minusBtn) {
            const wrapper =
                e.target.closest('.cart-controls') ||
                e.target.closest('.basket-controls');

            const skuId = wrapper.dataset.skuId;

            if (cartLocks[skuId]) return;
            cartLocks[skuId] = true;

            const unit = wrapper.dataset.unit;
            const step = unit === 'kg' ? 0.5 : 1;
            const delta = plusBtn ? step : -step;

            wrapper.classList.add('cart-loading');

            fetch(`/cart/update/${skuId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ delta })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                const qtyEl =
                    wrapper.querySelector('.cart-qty') ||
                    wrapper.querySelector('.basket-qty');

                if (qtyEl) {
                    qtyEl.innerText = data.item_count;
                }

                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.innerText = data.cart_count;
                }

                // CARD
                if (data.item_count <= 0 && wrapper.classList.contains('cart-controls')) {
                    wrapper.classList.add('d-none');
                    if (wrapper.previousElementSibling) {
                        wrapper.previousElementSibling.classList.remove('d-none');
                    }
                }

                // BASKET
                if (data.item_count <= 0 && wrapper.classList.contains('basket-controls')) {
                    const row = wrapper.closest('tr');
                    if (row) row.remove();
                }

                const totalEl = document.getElementById('basket-total');
                if (totalEl && data.total_sum !== undefined) {
                    const currency = totalEl.innerText.split(' ').pop();
                    totalEl.innerText = data.total_sum + ' ' + currency;
                }
            })
            .finally(() => {
                cartLocks[skuId] = false;
                wrapper.classList.remove('cart-loading');
            });
        }

    });

});
