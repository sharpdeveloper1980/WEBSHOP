@include('categories_menu', [
    'categories' => $categories, 
    'topLevelCategory' => !empty($topLevelCategory) ? $topLevelCategory : null, 
    'selectedCategory' => !empty($selectedCategory) ? $selectedCategory : null,
    'language' => $language
])

<div class="mt-4 mb-5">
    <div class="media product-page">
        @if(count($product['photos']) > 0)
            <div class="product_images_block">
                <div class="product_images">
                    @foreach($product['photos'] as $photo)
                        <img width="100" class="webshop-product-thumb" src="{{$photo}}" alt="{{!empty($product['product_name'][$language]) ? $product['product_name'][$language] : ''}}">
                    @endforeach
                </div>
                <div class="selected_image">
                    <img width="300" class="mr-3 webshop-product-image" src="{{$product['photos'][0]}}" alt="{{!empty($product['product_name'][$language]) ? $product['product_name'][$language] : ''}}">
                </div>
            </div>
        @endif
        <div class="media-body">
            @if(!empty($product['native_name']))
                <h3 class="product-title">{{ $product['native_name'] }}</h3>
            @elseif(!empty($product['product_name'][$language]))
                <h3 class="product-title">{{ $product['product_name'][$language] }}</h3>
            @endif

            @if($product['discount'])
                <p class="mb card-text price discount">
                    {{ number_format($product['price'] - ($product['price'] * ($product['discount'] / 100)), 2, ',', '') }} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}
                    <small><strike>{{$product['price']}} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}</strike></small>
                </p>
            @else
                <p class="mb card-text price">{{ number_format($product['price'], 2, ',', '') }} {{ $product['currency'] === 'EUR' ? '€' : $product['currency'] }}</p>
            @endif
                
            <div class="product_description">
                <b>{{ __('interface.product_description') }}: </b>
                @if(!empty($product['description'][$language]))
                    <span class="card-text product-description">{{ $product['description'][$language] }}</span>
                @endif
            </div>
                
            <p>
                <b>{{ __('interface.seller') }}:</b> 
                <a href="{{url('/seller/'.$product['client'][0]['id'])}}" data-embed_url="{{url('/embed/seller/'.$product['client'][0]['id'])}}" class="webshop-page-link seller-link">
                    {{ !empty($product['client'][0]['nickname']) ? $product['client'][0]['nickname'] : $product['client'][0]['id'] }}
                </a>
            </p>
            @if($display_store)
                <p>
                    <b>{{ __('interface.store') }}:</b> 
                    <a href="{{url('/embed/store/'.$product['store']['technical_name'])}}" class="webshop-page-link store-link">{{$product['store']['name']}}</a>
                </p>
            @endif
            @if($product['table_name'])
                <p class="card-text"><b>{{ __('interface.table') }}:</b> {{ $product['table_name'] }}</p>
            @endif
        </div>
    </div>
</div>

<div id="webshop-image-modal" class="modal">
    <span class="webshop-modal-close">&times;</span>
    <img id="webshop-modal-content">
</div>