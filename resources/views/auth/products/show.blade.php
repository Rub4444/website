@extends('auth.layouts.master')

@section('title', 'Ապրանք ' . $product->name)

@section('content')
<div class="col-md-12">
    <!-- Ապրանքի քարտ -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">{{ $product->name }}</h1>

            <div class="row">
                <!-- Աρισյին սյունակ (պատկեր և հիմնական տվյալներ) -->
                <div class="col-md-4">
                    <div class="text-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}" style="max-height: 300px;">
                        @else
                            <p>Պատկեր չկա</p>
                        @endif
                    </div>
                </div>

                <!-- Ցուցանիշային սյունակ (Ապրանքի տվյալներ) -->
                <div class="col-md-8">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th>Պաշտոն</th>
                                <th>Տվյալներ</th>
                            </tr>
                            <tr>
                                <td>ID</td>
                                <td>{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td>Կոդ</td>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <td>Անուն</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td>Անուն (en)</td>
                                <td>{{ $product->name_en }}</td>
                            </tr>
                            <tr>
                                <td>Նկարագրություն</td>
                                <td>{{ $product->description }}</td>
                            </tr>
                            <tr>
                                <td>Նկարագրություն (en)</td>
                                <td>{{ $product->description_en }}</td>
                            </tr>
                            <tr>
                                <td>Կատեգորիա</td>
                                <td>{{ $product->category ? $product->category->name : 'Ուսումնական կատեգորիա չկա' }}</td>
                            </tr>
                            <tr>
                                <td>Հատկանիշներ</td>
                                <td>
                                    @if($product->isNew())
                                        <span class="badge bg-success">Նոր</span>
                                    @endif
                                    @if($product->isRecommend())
                                        <span class="badge bg-warning">Խորհուրդ է տրվում</span>
                                    @endif
                                    @if($product->isHit())
                                        <span class="badge bg-danger">Հիթ</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Ապրանքի գործողություններ -->
    <div class="d-flex justify-content-end">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Փոփոխել
        </a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Արժանավոր եք, որ ցանկանում եք ջնջել այս ապրանքը?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Ջնջել
            </button>
        </form>
    </div>
</div>
@endsection
