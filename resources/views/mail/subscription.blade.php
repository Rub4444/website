<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <style>
        .notification {
            max-width: 500px;
            margin: 20px auto;
            background-color: #f9f9f9;
            border-left: 6px solid #28a745;
            padding: 20px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .notification h3 {
            margin-top: 0;
            color: #28a745;
        }
        .notification a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .notification a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="notification">
    <h3>💚 Հարգելի հաճախորդ,</h3>

    @if ($sku->category)
        <p>🎉 <a href="{{ route('sku', [$sku->category->code, $sku->code]) }}">
            {{ $sku->__('name') }}</a> ապրանքը արդեն առկա է մեր կայքում:</p>
    @else
        <p>🆕 Մեր տեսականին թարմացել է, այցելեք մեր կայքը նոր առաջարկները տեսնելու համար։</p>
    @endif

    <p style="margin-top: 20px;">🙏 Շնորհակալ ենք, որ օգտվում եք <strong>Իջևան Մարկետ</strong> հարթակից։</p>
</div>

</body>
</html>
