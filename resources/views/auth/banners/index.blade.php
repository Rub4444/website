@extends('auth.layouts.master')

@section('content')
    <div class="container">
        <h1>Բաններներ</h1>
        <a href="{{ route('banners.create') }}" class="btn btn-success mb-3">Ավելացնել Բաններ</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Տես․</th>
                    <th>Վերնագիր</th>
                    <th>Հղում</th>
                    <th>Գործողություն</th>
                </tr>
            </thead>
            <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td><img src="{{ Storage::url($banner->image) }}" width="200" class="rounded"></td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->link }}</td>
                    <td>
                        <form method="POST" action="{{ route('banners.destroy', $banner) }}"
                            onsubmit="return confirm('Вы уверены, что хотите удалить этот баннер?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Հեռացնել</button>
                        </form>
                        <br>
                        <a href="{{ route('banners.edit', $banner) }}" class="btn btn-primary btn-sm mb-1">Փոփոխել</a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="container py-4">
    <h2>📊 Статистика посещений</h2>

    <div class="row mb-4">
        <div class="col-md-4"><div class="card p-3">Всего визитов: <b>{{ $totalVisits }}</b></div></div>
        <div class="col-md-4"><div class="card p-3">Уникальных посетителей: <b>{{ $uniqueVisitors }}</b></div></div>
        <div class="col-md-4"><div class="card p-3">Сегодня: <b>{{ $todayVisits }}</b></div></div>
    </div>

    <canvas id="visitsChart" height="100"></canvas>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>🌍 Устройства</h5>
            <ul>
                @foreach($devices as $device => $count)
                    <li>{{ ucfirst($device) }} — {{ $count }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h5>🖥 Браузеры</h5>
            <ul>
                @foreach($browsers as $browser => $count)
                    <li>{{ $browser }} — {{ $count }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <h5 class="mt-4">🕵️ Последние визиты</h5>
    <table class="table">
        <thead>
            <tr>
                <th>IP</th>
                <th>Устройство</th>
                <th>Браузер</th>
                <th>Страница</th>
                <th>Время</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lastVisits as $visit)
            <tr>
                <td>{{ $visit->ip }}</td>
                <td>{{ $visit->device }}</td>
                <td>{{ $visit->browser }}</td>
                <td>{{ $visit->path }}</td>
                <td>{{ $visit->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('visitsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData->keys()) !!},
        datasets: [{
            label: 'Визиты за 7 дней',
            data: {!! json_encode($chartData->values()) !!},
            borderColor: 'green',
            tension: 0.3,
            fill: false
        }]
    }
});
</script>

@endsection
