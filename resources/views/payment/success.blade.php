@php
    $paymentId = $data['PaymentID'] ?? $data['MDOrderID'] ?? null;
@endphp

<h1>✅ Оплата прошла успешно</h1>

<p><strong>OrderID:</strong> {{ $data['OrderID'] ?? 'не найден' }}</p>
<p><strong>ResponseCode:</strong> {{ $data['ResponseCode'] ?? '—' }}</p>
<p>Сумма: {{ $data['Amount'] ?? '—' }} AMD</p>
<p>Дата: {{ $data['DateTime'] ?? '—' }}</p>

@if ($paymentId)
    <a href="{{ url('/payment/cancel/' . $paymentId) }}" style="color:red;">❌ Отменить оплату</a><br>
    <a href="{{ url('/payment/refund/' . $paymentId) }}" style="color:green;">💸 Сделать возврат</a>
@else
    <p style="color:orange;">⚠️ PaymentID не найден — отмена и возврат недоступны.</p>
@endif
