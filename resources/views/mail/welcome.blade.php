<!DOCTYPE html>
<html lang="hy">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f6fa; margin:0; padding:0; color:#333; }
    .container { max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .header { background-color: #2E8B57; color: #fff; padding: 20px; text-align: center; }
    .header h1 { margin:0; font-size: 24px; }
    .content { padding: 20px; }
    h1 { color: #2c3e50; }
    p { line-height: 1.6; }
    .btn { display:inline-block; padding:10px 20px; margin-top:15px; background-color:#35A212; color:#fff; text-decoration:none; border-radius:6px; font-weight:bold; }
    .footer { text-align:center; font-size:0.85em; color:#7f8c8d; padding:15px; }
    @media (max-width: 600px) { .container { width: 95%; } h1 { font-size: 20px; } }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Իջևան Մարկետ</h1>
    </div>
    <div class="content">
        <h1>Բարև հարգելի {{ $user->name }} 😊!</h1>
        <p>🎉 Շնորհակալություն, որ միացար Իջևան Մարկետ-ին։</p>
        <a href="https://ijevanmarket.am" class="btn">Մուտք գործել մեր հարթակ</a>
        <p>🛍️ Մաղթում ենք հաճելի գնումներ մեր հարթակում։</p>
    </div>
    <div class="footer">
        💚 Սիրով՝ Իջևան Մարկետ թիմ
    </div>
</div>
</body>
</html>
