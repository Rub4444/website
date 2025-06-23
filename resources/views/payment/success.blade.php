@php
    $paymentId = $data['PaymentID'] ?? $data['MDOrderID'] ?? null;
@endphp

<h1>✅ Оплата прошла успешно</h1>

<p><strong>OrderID:</strong> {{ $data['OrderID'] ?? 'не найден' }}</p>
<p><strong>PaymentID:</strong> {{ $paymentId ?? 'не найден' }}</p>
<p><strong>ResponseCode:</strong> {{ $data['ResponseCode'] ?? '—' }}</p>
<p>Сумма: {{ $data['Amount'] ?? '—' }} AMD</p>
<p>Дата: {{ $data['DateTime'] ?? '—' }}</p>

@if ($paymentId)
    <form method="POST" action="{{ url('/payment/cancel') }}">
        @csrf
        <input type="hidden" name="paymentId" value="{{ $paymentId }}">
        <button type="submit" style="color:red;">❌ Отменить оплату</button>
    </form>

    <form method="POST" action="{{ url('/payment/refund') }}">
        @csrf
        <input type="hidden" name="paymentId" value="{{ $paymentId }}">
        <button type="submit" style="color:green;">💸 Сделать возврат</button>
    </form>
@else
    <p style="color:orange;">⚠️ PaymentID не найден — отмена и возврат недоступны.</p>
@endif
