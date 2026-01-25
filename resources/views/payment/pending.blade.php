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
const statusUrl = "/payment/status/{{ $order->id }}";
let tries = 0;

console.log('⏳ Pending page loaded');
console.log('🔗 Status URL:', statusUrl);

const interval = setInterval(async () => {
    tries++;
    console.log(`🔄 Try #${tries}`);

    try {
        const res = await fetch(statusUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await res.json();
        console.log('📦 Response:', data);

        if (data.invoice_status === 'PAID') {
            clearInterval(interval);
            window.location.href = "/?payment=success";
        }

        if (data.invoice_status === 'REJECTED') {
            clearInterval(interval);
            window.location.href = "/?payment=fail";
        }

    } catch (e) {
        console.warn('Waiting for payment...');
    }

    if (tries > 20) {
        clearInterval(interval);
        window.location.href = "/?payment=timeout";
    }
}, 3000);
</script>
