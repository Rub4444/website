Հարգելի հաճախորդ, @if ($sku->category)
                    <a href="{{ route('sku', [$sku->category->code, $sku->code]) }}">{{$sku->__('name')}}</a> ապրանքը արդեն առկա է
                @else
                մեր տեսականին թարմացել է
                @endif

