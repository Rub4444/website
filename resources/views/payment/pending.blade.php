@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 text-center p-4">
                <h2>Վճարումը մշակվում է…</h2>
                <p>Խնդրում ենք սպասել</p>

                <div style="margin-top:20px">
                    ⏳
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        window.location.reload();
    }, 3000);
</script>
@endsection
