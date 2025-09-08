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

    <div class="footer">
        Շնորհակալություն, որ ընտրել եք մեզ!
    </div>
</body>
</html>
