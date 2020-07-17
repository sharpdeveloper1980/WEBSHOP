<div class="webshop-row align-items-center mt-4">
    <div class="col-md-3 webshop-page-count">
        <span>{{$totalItemsCount}} {{ __('interface.products') }}, {{ __('interface.page') }} {{$activePage}} {{ __('interface.of') }} {{$last_page}}</span>
    </div>
    @if(empty($hide_search))
        <div class="col-md-5">
            <div class="mb-3 input-group">
                <input placeholder="{{!empty($placeholder) ? $placeholder : __('interface.global_search_placeholder') }}" aria-label="Search" aria-describedby="basic-addon2"
                       class="webshop-product-search form-control" value="">
                <div class="input-group-append">
                    <button type="button" class="webshop-search-button search-button btn btn-outline-secondary">
                        <svg viewBox="0 0 512 512" class="svg-icon " xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor"
                                  d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @else
        @if(!empty($breadcrumbs))
            <h1 class="col-md-5 category-title">{{end($breadcrumbs)['name']}}</h1>
        @else
            <h1 class="col-md-5 category-title">{{ __('interface.all_products') }}</h1>
        @endif
    @endif
    <div class="col-md-2 webshop-sort-select">
        <select name="sort" class="form-control webshop-products-sort">
            <option value="created:desc" selected>{{ __('interface.latest') }}</option>
            <option value="created:asc">{{ __('interface.earliest') }}</option>
            <option value="price:asc">{{ __('interface.cheapest_first') }}</option>
            <option value="price:desc">{{ __('interface.expensive_first') }}</option>
            <option value="title:asc">{{ __('interface.title_asc') }}</option>
            <option value="title:desc">{{ __('interface.title_desc') }}</option>
        </select>
    </div>
    <div class="col-md-2 webshop-products-view-options">
        <span class="webshop-list-type-buttons webshop-row">
            <div id="webshop-product-list-view" class="webshop-product-view button-square"
                 data-viewclass="webshop-product-list-view">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor"
                          d="M88 56H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16V72a16 16 0 0 0-16-16zm0 160H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16v-48a16 16 0 0 0-16-16zm0 160H40a16 16 0 0 0-16 16v48a16 16 0 0 0 16 16h48a16 16 0 0 0 16-16v-48a16 16 0 0 0-16-16zm416 24H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-320H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8V88a8 8 0 0 0-8-8zm0 160H168a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h336a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z"></path>
                </svg>
            </div>
            <div id="webshop-product-grid-view" class="webshop-product-view button-square active"
                 data-viewclass="webshop-product-grid-view">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor"
                          d="M0 80v352c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48H48C21.49 32 0 53.49 0 80zm240-16v176H32V80c0-8.837 7.163-16 16-16h192zM32 432V272h208v176H48c-8.837 0-16-7.163-16-16zm240 16V272h208v160c0 8.837-7.163 16-16 16H272zm208-208H272V64h192c8.837 0 16 7.163 16 16v160z"></path>
                </svg>
            </div>
        </span>
    </div>
</div>