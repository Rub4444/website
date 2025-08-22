<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Заказ отменен</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        h2 { color: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #555; }
    </style>
</head>
<body>
    <h2>Ваш заказ #{{ $order->id }} был отменен</h2>

    <p>Здравствуйте, {{ $order->name }}!</p>

    <p>К сожалению, ваш заказ был отменен по следующей причине:</p>
    <p><strong>{{ $cancellationComment }}</strong></p>

    <p>Список товаров, которые были в заказе:</p>
    <table>
        <thead>
            <tr>
                <th>Товар</th>
                <th>Количество</th>
                <th>Цена за единицу</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->skus as $sku)
            <tr>
                <td>{{ $sku->product->__('name') }}</td>
                <td>{{ $sku->pivot->count }}</td>
                <td>{{ number_format($sku->price, 2) }} {{ $order->currency->code ?? 'AMD' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Товары возвращены обратно в вашу корзину. Вы можете оформить новый заказ в любое время.</p>

    <div class="footer">
        Спасибо, что выбираете наш магазин!
    </div>
</body>
</html>
