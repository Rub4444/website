<form method="GET" action="{{ route('shop') }}">
    <div class="mb-3 col-6">
        {{-- <label class="form-label">Կատեգորիա</label> --}}
        <select name="category" class="form-select form-select-sm" style="max-width: 100%;">
            <option value="">@lang('main.all_categories')</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3 col-6">
        <input placeholder="@lang('main.price_from')" type="number" name="min_price" class="form-control form-control-sm" style="max-width: 100%;" value="{{ request('min_price') }}">
    </div>

    <div class="mb-3 col-6">
        <input placeholder="@lang('main.price_to')" type="number" name="max_price" class="form-control form-control-sm" style="max-width: 100%;" value="{{ request('max_price') }}">
    </div>

    <div class="mb-3 col-6">
        <select name="sort" class="form-select form-select-sm" style="max-width: 100%;">
            <option value="">@lang('main.default')</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>@lang('main.cheap_from')</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>@lang('main.expensive_from')</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success w-100 mb-2">@lang('main.filter')</button>
    <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">
        @lang('main.properties.reset')
    </a>
</form>
