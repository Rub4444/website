<p>
Հարգելի {{$name}}
Ձեր պատվերը գրանցված է
Ընդհանուր արժեքը՝ {{$fullSum}} դրամ
Շնորհակալություն գնումների համար
</p>

<table>
    <tbody>
        @foreach($order->products as $product)
            <tr>
                <td>
                    <a href="{{ route('product', [$product->category->code, $product->code]) }}">
                        {{ $product->__('name') }}
                    </a>
                </td>
                <td>
                    <span class="badge" style="color:black;">{{ $product->pivot->count }}</span>
                </td>
                <td>{{ $product->price }} AMD</td>
                <td>{{ $product->getPriceForCount() }} AMD</td>
            </tr>
        @endforeach
    </tbody>
</table>
