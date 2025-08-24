@extends('auth.layouts.master')

@isset($product)
    @section('title', 'Խմբագրել ապրանքը՝ ' . $product->name)
@else
    @section('title', 'Ստեղծել ապրանք')
@endisset

@section('content')
    <div class="container py-4">
        <div class="card shadow rounded-4">
            <div class="card-body">
                @isset($product)
                    <h2 class="mb-4 text-center" style="color:#2E8B57;">Խմբագրել ապրանք՝ <b>{{ $product->name }}</b></h2>
                @else
                    <h2 class="text-success mb-4">Ավելացնել ապրանք</h2>
                @endisset

                <form method="POST" enctype="multipart/form-data"
                      @isset($product)
                          action="{{ route('products.update', $product) }}"
                      @else
                          action="{{ route('products.store') }}"
                      @endisset>

                    @isset($product)
                        @method('PUT')
                    @endisset
                    @csrf

                    {{-- Code --}}
                    <div class="mb-3">
                        <label for="code" class="form-label">Կոդ</label>
                        @include('auth.layouts.error', ['fieldName' => 'code'])
                        <input type="text" class="form-control" name="code" id="code"
                               value="@isset($product){{ $product->code }}@endisset">
                    </div>

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Անվանում (հայերեն)</label>
                        @include('auth.layouts.error', ['fieldName' => 'name'])
                        <input type="text" class="form-control" name="name" id="name"
                               value="@isset($product){{ $product->name }}@endisset">
                    </div>

                    {{-- Name EN --}}
                    <div class="mb-3">
                        <label for="name_en" class="form-label">Անվանում (անգլերեն)</label>
                        @include('auth.layouts.error', ['fieldName' => 'name_en'])
                        <input type="text" class="form-control" name="name_en" id="name_en"
                               value="@isset($product){{ $product->name_en }}@endisset">
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Կատեգորիա</label>
                        @include('auth.layouts.error', ['fieldName' => 'category_id'])
                        <select name="category_id" id="category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @isset($product)
                                        @if($product->category_id == $category->id) selected @endif
                                    @endisset
                                >{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Նկարագրություն (հայերեն)</label>
                        @include('auth.layouts.error', ['fieldName' => 'description'])
                        <textarea name="description" id="description" class="form-control" rows="5">@isset($product){{ $product->description }}@endisset</textarea>
                    </div>

                    {{-- Description EN --}}
                    <div class="mb-3">
                        <label for="description_en" class="form-label">Նկարագրություն (անգլերեն)</label>
                        @include('auth.layouts.error', ['fieldName' => 'description_en'])
                        <textarea name="description_en" id="description_en" class="form-control" rows="5">@isset($product){{ $product->description_en }}@endisset</textarea>
                    </div>

                    {{-- Image --}}
                    {{-- <div class="mb-3">
                        <label for="image" class="form-label">Նկար</label>
                        <input type="file" class="form-control" name="image" id="image">
                    </div> --}}

                 <label for="unit">Կգ/Հատ</label>
                <select name="unit" id="unit" class="form-control">
                    <option value="pcs" {{ old('unit', $product->unit ?? '') === 'pcs' ? 'selected' : '' }}>Հատ</option>
                    <option value="kg"  {{ old('unit', $product->unit ?? '') === 'kg' ? 'selected' : '' }}>Կգ</option>
                </select>


                    {{-- Properties
                    <div class="mb-3">
                        <label for="property_id[]" class="form-label">Հատկություններ</label>
                        @include('auth.layouts.error', ['fieldName' => 'property_id[]'])
                        <select name="property_id[]" id="property_id" class="form-select" multiple>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}"
                                    @isset($product)
                                        @if($product->properties->contains($property->id)) selected @endif
                                    @endisset
                                >{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    {{-- Properties --}}
                    <div class="mb-3">
                        <label for="property_id[]" class="form-label">Հատկություններ</label>
                        @include('auth.layouts.error', ['fieldName' => 'property_id[]'])
                        <select name="property_id[]" id="property_id" class="form-select" multiple>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}"
                                    @isset($product)
                                        @if($product->properties->contains($property->id)) selected @endif
                                    @endisset
                                >{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    {{-- Checkboxes --}}
                    <div class="mb-4">
                        @foreach([
                            'hit' => 'Հիթ',
                            'new' => 'Նոր',
                            'recommend' => 'Առաջարկվող'] as $field => $title)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1"
                                    @if(isset($product) && $product->$field === 1) checked @endif>
                                <label class="form-check-label" for="{{ $field }}">{{ $title }}</label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Submit --}}
                    <div class="text-end">
                        <button class="btn btn-success px-4 rounded-pill">Պահպանել</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function() {
    const select = document.getElementById("property_id");

    // Запоминаем последнее состояние
    let lastClicked = null;

    select.addEventListener("mousedown", function(e) {
        e.preventDefault(); // отменяем стандартное выделение

        const option = e.target;
        if (option.tagName === "OPTION") {
            option.selected = !option.selected; // переключаем вручную
            option.dispatchEvent(new Event("change", { bubbles: true }));
        }
    });
});
</script>
