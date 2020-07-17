@include('categories_menu', [
    'categories' => $categories, 
    'topLevelCategory' => !empty($topLevelCategory) ? $topLevelCategory : null, 
    'selectedCategory' => !empty($selectedCategory) ? $selectedCategory : null,
    'language' => $language
])

<div class="jumbotron mt-4 mb-4">
    <div class="webshop-row">
        <div class="col-md-2 store-img">
            @if($store['logo_url'])
                <img class="img-fluid rounded-circle" src="{{$store['logo_url']}}">
            @endif
        </div>
        <div class="col-md-10 pl-40">
            <h1 class="store-name">
                <a class="zellrs-dark-green webshop-page-link" href="{{url('/store/'.$store['technical_name'])}}" data-embed_url="{{url('/embed/store/'.$store['technical_name'])}}">{{$store['name']}}</a>
            </h1>
            <p>{!! $store['company_description'] !!}</p>
            <div class="webshop-row">
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">{{ __('interface.opening_hours') }}:</b>
                    <p>{!! $store['company_business_hours'] !!}</p>
                </div>
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">{{ __('interface.contact') }}:</b>
                    <p>{!! $store['company_contact_info'] !!}</p>
                </div>
                <div class="col-md-4 col-12">
                    <b class="zellrs-grey">
                        {{ __('interface.website') }}:
                        @if($store['company_website_url'])
                            <a class="zellrs-dark-green" target="_blank" href="{{$store['company_website_url']}}">{{$store['company_website_url']}}</a>
                        @endif
                    </b>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.filterbar', [
    'totalItemsCount' => $paginated_products->total(), 
    'activePage' => $paginated_products->currentPage(),
    'last_page' => $paginated_products->lastPage(),
    'placeholder' => __('interface.store_search')
    ])

@include('partials.products', ['paginated_products' => $paginated_products,  'display_store' => false, 'language' => $language])