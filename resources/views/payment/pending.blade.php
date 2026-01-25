@extends('layouts.master')

@section('content')

<div style="text-align:center; padding:40px">
    <h2>Վճարումը մշակվում է…</h2>
    <p>Խնդրում ենք սպասել</p>

    <div style="margin-top:20px">
        ⏳
    </div>
</div>

<script>
setInterval(async () => {
    const res = await fetch('/payment/status/{{ $order->id }}');
    const data = await res.json();

    if (data.status === 'PAID') {
        window.location.href = '/payment/success/{{ $order->id }}';
    }

    if (data.invoice_status === 'REJECTED') {
        window.location.href = '/payment/fail/{{ $order->id }}';
    }
}, 2000);
</script>

@endsection
