{{-- resources/views/errors/500.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen text-center bg-gray-100">
    <h1 class="text-6xl font-bold text-red-600 mb-4">500</h1>
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
        Ինչ-որ բան այնպես չգնաց
    </h2>
    <p class="text-gray-600 mb-6">
        Մենք արդեն աշխատում ենք լուծման վրա։ Խնդրում ենք փորձել ավելի ուշ:
    </p>
    <a href="{{ url('/') }}"
       class="px-6 py-3 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700">
        Վերադառնալ Գլխավոր էջ
    </a>
</div>
@endsection
