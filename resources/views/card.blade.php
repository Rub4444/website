<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <div class="labels">
            @if($product->isNew())
                <span class="badge badge-success">New</span>
            @endif
            @if($product->isRecommend())
                <span class="badge badge-warning">Recommend</span>
            @endif
            @if($product->isHit())
                <span class="badge badge-danger">Hit</span>
            @endif
        </div>
        <img src="{{ Storage::url($product->image) }}" alt="" style="width:100px;height:100px;">
        <div class="caption">
            <h3>{{$product->name}}</h3>
            <p>{{$product->price}} AMD</p>
            <p>
                <form action="{{route('basket-add', $product)}}" method="POST">
                    <button type="submit">Cart</button>
                    <a href="{{route('product', [isset($category) ?  $category->code : $product->category->code, $product->code])}}">More...</a>
                    @csrf
                </form>
            </p>
        </div>
    </div>
</div>
