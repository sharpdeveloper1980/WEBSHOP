<div class="flex-center top-bar-left">
    <span class="webshop-go-back arrow arrow-left"></span>
    <a href="{{url('/')}}"
       data-embed_url="{{url('/embed/products/')}}" 
       class="webshop-page-link">
        @if(!empty($logo_img_url))
            <img class="webshop-domain_logo" src="{{$logo_img_url}}">
        @else
            {{ __('interface.home') }}
        @endif
    </a>
</div>
<div class="mb-3 input-group top-bar-right">
    <input placeholder="{{ __('interface.global_search_placeholder') }}" class="webshop-global-search form-control">
    <div class="input-group-append">
        <button type="button" class="webshop-global-search-button search-button btn btn-outline-secondary">
            <svg viewBox="0 0 512 512" class="svg-icon " xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z"></path>
            </svg>
        </button>
    </div>
    <div class="webshop-hamburger-menu">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M20,7H4C3.4,7,3,6.6,3,6s0.4-1,1-1h16c0.6,0,1,0.4,1,1S20.6,7,20,7z"></path>
            <path d="M15.2,13H4c-0.6,0-1-0.4-1-1s0.4-1,1-1h11.2c0.6,0,1,0.4,1,1S15.8,13,15.2,13z"></path>
            <path d="M20,19H4c-0.6,0-1-0.4-1-1s0.4-1,1-1h16c0.6,0,1,0.4,1,1S20.6,19,20,19z"></path>
        </svg>
    </div>
</div>