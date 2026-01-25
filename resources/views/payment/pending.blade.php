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

        console.log('📡 HTTP status:', res.status);

        const data = await res.json();
        console.log('📦 Response:', data);

        if (data.invoice_status === 'PAID') {
            console.log('✅ PAID → redirect');
            clearInterval(interval);
            window.location.href = "/payment/success/{{ $order->id }}";
        }

        if (data.invoice_status === 'REJECTED') {
            console.log('❌ REJECTED → redirect');
            clearInterval(interval);
            window.location.href = "/payment/fail/{{ $order->id }}";
        }

    } catch (e) {
        console.error('⚠️ Fetch failed:', e);
    }

    if (tries > 20) {
        clearInterval(interval);
        console.warn('⌛ Timeout waiting payment');
    }
}, 5000);
</script>
