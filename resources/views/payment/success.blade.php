<h1>✅ Оплата прошла успешно</h1>

<p><strong>OrderID:</strong> {{ $data['OrderID'] ?? 'не найден' }}</p>
<p><strong>PaymentID:</strong> {{ $data['PaymentID'] ?? ($data['MDOrderID'] ?? 'не найден') }}</p>

<p><strong>ResponseCode:</strong> {{ $data['ResponseCode'] }}</p>


<p>Сумма: {{ $data['Amount'] }} AMD</p>
<p>Дата: {{ $data['DateTime'] }}</p>


<a href="{{ url('/payment/cancel/' . $data['PaymentID']) }}" style="color:red;">❌ Отменить оплату</a><br>
<a href="{{ url('/payment/refund/' . $data['PaymentID']) }}" style="color:green;">💸 Сделать возврат</a>
