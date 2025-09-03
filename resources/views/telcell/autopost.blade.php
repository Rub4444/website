@extends('layouts.app') {{-- Եթե ունես layout --}}
@section('content')
 {!! $formHtml !!}
    <script>
        // Автоматически отправляем форму
        const form = document.querySelector('form');
        if (form) {
            form.submit();
        }
    </script>
@endsection
