<div class="mb-4 webshop-row products-listing">
    @foreach($paginated_products as $product)
        <div class="webshop-product-item webshop-product-grid-view">
            <div class="card">
                <div class="condition-webshop-row">
                    <div class="webshop-image-block">
                        <a href="{{url('/product/'.$product['id'])}}" data-embed_url="{{url('/embed/product/'.$product['id'])}}" class="webshop-page-link nav-link">
                            @if($product['photos'][0])
                                <img class="card-img-top webshop-product-img" src="{{ $product['photos'][0] }}">
                            @endif
                        </a>
                    </div>
                    
                        <div class="card-body webshop-ptitle-block">
                            <div class="card-title h5">
                                <a href="{{url('/product/'.$product['id'])}}" data-embed_url="{{url('/embed/product/'.$product['id'])}}" class="webshop-page-link">
                                    @if(!empty($product['native_name']))
                                        {{ $product['native_name'] }}
                                    @elseif(!empty($product['product_name'][$language]))
                                        {{ $product['product_name'][$language] }}
                                    @endif
                                </a>
                            </div>
                            @if(!empty($product['description'][$language]))
                                <p class="card-text">{{ $product['description'][$language] }}</p>
                            @endif
                        </div>

                        <div class="card-body webshop-price-block">
                            @if($product['discount'])
                                <p class="card-text price discount">
                                    {{ number_format($product['price'] - ($product['price'] * ($product['discount'] / 100)), 2, ',', '') }} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}
                                    <small><strike>{{$product['price']}} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}</strike></small>
                                </p>
                            @else
                                <p class="card-text price">{{ number_format($product['price'], 2, ',', '') }} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}</p>
                            @endif

                            @if(!isset($hide_seller))
                                <a href="{{url('/seller/'.$product['client'][0]->id)}}" data-embed_url="{{url('/embed/seller/'.$product['client'][0]->id)}}" class="webshop-page-link">
                                    <p class="card-text">{{ !empty($product['client'][0]->nickname) ? $product['client'][0]->nickname : $product['client'][0]->id }}</p>
                                </a>
                            @endif

                            @if($display_store)
                                <a href="{{url('/store/'.$product['store']['technical_name'])}}" data-embed_url="{{url('/embed/store/'.$product['store']['technical_name'])}}" class="webshop-page-link">
                                    <p class="card-text">{{ $product['store']['name'] }}</p>
                                </a>
                            @endif

                            <p class="card-text table-name">
                                {{ __('interface.table') }}: {{$product['table_name'] ? $product['table_name'] : '-'}}
                            </p>
                        </div>
                    
                </div>
            </div>
        </div>
    @endforeach
</div>

{{ $paginated_products->links() }}