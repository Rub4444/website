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
        window.location.href = '/payment/return?order={{ $order->id }}&status=success';
    }

    if (data.status === 'REJECTED') {
        window.location.href = '/payment/return?order={{ $order->id }}&status=fail';
    }
}, 3000);
</script>

@endsection
