@include('categories_menu', [
    'categories' => $categories, 
    'topLevelCategory' => !empty($topLevelCategory) ? $topLevelCategory : null, 
    'selectedCategory' => !empty($selectedCategory) ? $selectedCategory : null,
    'language' => $language
])

<div class="jumbotron mt-4 mb-4">
    <div class="webshop-row">
        <div class="col-md-2 seller-img">
            @if(!empty($seller['seller_picture_url']))
                <img class="img-fluid rounded-circle" src="{{$seller['seller_picture_url']}}">
            @endif
        </div>
        <div class="col-md-10 pl-40">
            <h1 class="seller-name">
                <a href="{{url('/seller/'.$seller['id'])}}" data-embed_url="{{url('/embed/seller/'.$seller['id'])}}" class="zellrs-dark-green webshop-page-link">
                    {{$seller['nickname']}}
                </a>
            </h1>
            <div class="webshop-row">
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">{{ __('interface.about') }}:</b>
                    @if(!empty($seller['seller_description'][$seller['country']]))
                        <p>{!! $seller['seller_description'][$seller['country']] !!}</p>
                    @elseif(!empty($seller['seller_description'][$language]))
                        <p>{!! $seller['seller_description'][$language] !!}</p>
                    @endif
                </div>
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">{{ __('interface.store') }}:</b>
                    <a href="{{url('/store/'.$seller['store']['technical_name'])}}" data-embed_url="{{url('/embed/store/'.$seller['store']['technical_name'])}}" class="webshop-page-link store-link">
                        <p>{{$seller['store']['name']}}</p>
                    </a>
                </div>
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">{{ __('interface.business_name') }}:</b>
                    <p>
                        @if(!empty($seller['business_name']))
                            {{$seller['business_name']}}
                        @else
                            {{ __('interface.not_yet_chosen') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.filterbar', [
    'totalItemsCount' => $paginated_products->total(), 
    'activePage' => $paginated_products->currentPage(),
    'last_page' => $paginated_products->lastPage(),
    'placeholder' => __('interface.seller_search')
    ])

@include('partials.products', ['paginated_products' => $paginated_products, 'hide_seller' => true, 'language' => $language])