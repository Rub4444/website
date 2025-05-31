@extends('layouts.master')
@section('title', 'Իջևան Մարկետ')

@section('content')
    @if($order)
        <section class="py-5 bg-light rounded-top">
            <div class="container">
                <div id="basket-items">
                    @include('partials.basket_items', ['order' => $order, 'currencySymbol' => $currencySymbol])
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const basketItems = document.getElementById('basket-items');

            if (!basketItems) return;

            basketItems.addEventListener('click', function (e) {
                const addBtn = e.target.closest('.btn-add');
                const removeBtn = e.target.closest('.btn-remove');

                if (addBtn || removeBtn) {
                    const isAdd = !!addBtn;
                    const button = addBtn || removeBtn;
                    const skuId = button.dataset.id;

                    const url = isAdd
                        ? '{{ url("/basket/ajax/add") }}/' + skuId
                        : '{{ url("/basket/ajax/remove") }}/' + skuId;

                    const options = {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: isAdd ? JSON.stringify({ quantity: 1 }) : null
                    };

                    // Плавное скрытие перед обновлением
                    basketItems.style.opacity = 0.5;

                    fetch(url, options)
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(() => {
                                basketItems.innerHTML = data.html;
                                basketItems.style.opacity = 1;
                            }, 150); // Небольшая задержка для плавности
                        })
                        .catch(error => {
                            console.error('Ошибка:', error);
                            basketItems.style.opacity = 1;
                        });
                }
            });
        });
    </script>
@endpush



