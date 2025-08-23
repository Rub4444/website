<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Պատվերը չեղարկվել է</title>
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
    <h2>Ձեր պատվերը #{{ $order->id }} չեղարկվել է</h2>

    <p>Բարև, {{ $order->name }}!</p>

    <p>Ցավոք, ձեր պատվերը չեղարկվել է հետևյալ պատճառով․</p>
    <p><strong>{{ $cancellationComment }}</strong></p>

    <p>Պատվերի մեջ ընդգրկված ապրանքների ցուցակը․</p>
    <table>
        <thead>
            <tr>
                <th>Ապրանք</th>
                <th>Քանակ</th>
                <th>Գին (միավորի համար)</th>
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

    {{-- <p>Ապրանքները վերադարձվել են ձեր զամբյուղ։ Կարող եք ցանկացած ժամանակ ձևակերպել նոր պատվեր։</p> --}}

    <div class="footer">
        Շնորհակալություն, որ ընտրում եք մեր խանութը!
    </div>
</body>
</html>
