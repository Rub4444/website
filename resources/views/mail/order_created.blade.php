<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Պատվերի հաստատում</title>
</head>
<body>
    <h2>Հարգելի {{ $name }},</h2>

    <p>Ձեր պատվերը հաջողությամբ գրանցվել է։</p>

    <p><strong>Ընդհանուր գումարը՝</strong> {{ number_format($fullSum, 0, '.', ' ') }} դրամ</p>

    <h3>Պատվերի մանրամասները՝</h3>
    <ul>
        @foreach ($order->skus as $sku)
            <li>
                {{ $sku->product->name }} - {{ $sku->count }} հատ -
                {{ number_format($sku->price * $sku->count, 0, '.', ' ') }} դրամ
            </li>
        @endforeach
    </ul>

    <p>Շնորհակալություն գնումների համար։</p>
</body>
</html>
