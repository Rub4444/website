<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Հարգելի {{ $name }} 😊,</h2>

    <p>✅ Ձեր պատվերը հաջողությամբ գրանցվել է։</p>

    <p>
        🛻 Պատվերի տեսակը՝
        @if($order->delivery_type === 'pickup')
            <strong>Խանութից վերցնել</strong>
        @elseif($order->delivery_type === 'delivery')
            <strong>Առաքում</strong>
        @endif
    </p>
{{--
    <h5>🛒 Պատվերի մանրամասները՝</h5>
    <ul>
        @foreach ($order->skus as $sku)
            <li>
                📦 {{ $sku->product->name }} - {{ $sku->count }} հատ -
                {{ number_format($sku->price * $sku->count, 0, '.', ' ') }} դրամ
            </li>
        @endforeach
    </ul> --}}

    @if($order->delivery_type === 'delivery')
        <p>💵 Ընդհանուր գումարը՝ <strong>{{ number_format($fullSum+500, 0, '.', ' ') }} դրամ</strong></p>
    @else
        <p>💵 Ընդհանուր գումարը՝ <strong>{{ number_format($fullSum, 0, '.', ' ') }} դրամ</strong></p>
    @endif

    <p>🙏 Շնորհակալություն գնումների համար։</p>
    <p>✨ Մաղթում ենք հաճելի օր։</p>
</body>
</html>
