<p>
Հարգելի {{$name}}
Ձեր պատվերը գրանցված է
Ընդհանուր արժեքը՝ {{$fullSum}} դրամ
Շնորհակալություն գնումների համար
</p>

<table>
    <tbody>
        @foreach($order->skus as $sku)
            <tr>
                <td>
                    <a href="{{ route('sku', [$sku->product->category->code, $sku->product->code, $sku]) }}">
                        {{ $sku->product->__('name') }}
                    </a>
                </td>
                <td>
                    <span class="badge" style="color:black;">{{ $sku->countInOrder }}</span>
                </td>
                <td>{{ $sku->price }} AMD</td>
                <td>{{ $sku->getPriceForCount() }} AMD</td>
            </tr>
        @endforeach
    </tbody>
</table>
