@include('categories_menu', [
    'categories' => $categories,
    'language' => $language,
    'topLevelCategory' => !empty($topLevelCategory) ? $topLevelCategory : null, 
    'selectedCategory' => !empty($selectedCategory) ? $selectedCategory : null,
    'language' => $language
])

@if(!empty($hero_img_url))
    <div class="webshop-hero-image">
        <a target="_blank" href="{{$hero_img_link}}"><img height="200" src="{{$hero_img_url}}"></a>
    </div>
@endif
@if(!empty($commercial_html))
    <div class="commercial_block">
        {!! $commercial_html !!}
    </div>
@endif

@if(!empty($breadcrumbs))
    <div class="breadcrumbs">
        <span>
            <a href="{{url('/')}}"
               data-embed_url="{{url('/embed/products')}}"
               class="webshop-page-link">
                Home
            </a>
        </span>
        @foreach($breadcrumbs as $breadcrumb)
            <span>{{ ' → ' }}</span>
            <span>
                <a href="{{url('/products/?category='.$breadcrumb['id'])}}"
                   data-embed_url="{{url('/embed/products/?category='.$breadcrumb['id'])}}"
                   class="webshop-page-link">
                    {{ $breadcrumb['name'] }}
                </a>
            </span>
        @endforeach
    </div>
@endif

@include('partials.filterbar', [
    'totalItemsCount' => $paginated_products->total(), 
    'activePage' => $paginated_products->currentPage(),
    'last_page' => $paginated_products->lastPage(),
    'hide_search', !empty($hide_search) ? $hide_search : null,
    'breadcrumbs' => !empty($breadcrumbs) ? $breadcrumbs : null
    ])
@if($paginated_products->total() === 0)
    <div class="mb-4 mt-15-rem">
        <p>{{ __('interface.there_are_no_products') }}.</p>
    </div>
@else
    @include('partials.products', ['paginated_products' => $paginated_products, 'display_store' => $display_store, 'language' => $language])
@endif