<head>
    <link href="https://www.puotimo.fi/css/embed.css" rel="stylesheet">
</head>
<div class="navbar">
    @foreach ($categories as $category)
        <?php $is_subcat_has_commercial_block = false; ?>
        <div class="dropdown">
            <div class="dropbtn {{$topLevelCategory && $topLevelCategory->category_uid == $category['category_uid'] ? 'selected' : ''}}">
                <a href="{{url('/products/?category='.$category['category_uid'])}}" 
                   data-embed_url="{{url('/embed/products/?category='.$category['category_uid'])}}" 
                   data-category_uid="{{$category['category_uid']}}" 
                   data-category_toplevel_uid="{{$category['category_uid']}}" 
                   class="webshop-page-link top-level-category">
                    {{ !empty($category['name'][$language]) ? $category['name'][$language] : $category['name']['en'] }}
                </a>
            </div>
            <div class="dropdown-content">
                @foreach($category['children_categories'] as $child_cat)
                    <div class="header {{ !empty($category['commercial_html']) ? 'half' : '' }}">
                        <h2>
                            <a href="{{url('/products/?category='.$child_cat['category_uid'])}}" 
                               data-embed_url="{{url('/embed/products/?category='.$child_cat['category_uid'])}}"
                               data-category_uid="{{$child_cat['category_uid']}}"
                               data-category_toplevel_uid="{{$category['category_uid']}}"
                               class="webshop-page-link {{$selectedCategory && $selectedCategory === $child_cat['category_uid'] ? 'selected' : ''}}">
                               {{ !empty($child_cat['name'][$language]) ? $child_cat['name'][$language] : $child_cat['name']['en'] }}
                            </a>
                            <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M10,17a1,1,0,0,1-.707-1.707L12.586,12,9.293,8.707a1,1,0,0,1,1.414-1.414L15.414,12l-4.707,4.707A1,1,0,0,1,10,17Z"></path></svg></span>
                        </h2>
                        <div class="menu-row {{ !empty($child_cat['commercial_html']) ? 'half' : '' }}">
                            @include('partials.categories_menu', [
                                'category' => $child_cat, 
                                'parent_uid' => $category['category_uid'], 
                                'selectedCategory' => $selectedCategory,
                                'language' => $language
                            ])
                        </div>
                        @if(!empty($child_cat['commercial_html']))
                            <?php $is_subcat_has_commercial_block = true; ?>
                            <div class="commercial_block">
                                {!! $child_cat['commercial_html'] !!}
                            </div>
                        @endif
                    </div>
                @endforeach
                
                @if(!empty($category['commercial_html']) && !$is_subcat_has_commercial_block)
                    <div class="commercial_block">
                        {!! $category['commercial_html'] !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="breadcrumbs"></div>

<div class="webshop-mobile-menu hidden">
    <div class="webshop-mobile-nav-header" data-default_title="{{ __('interface.browse_categories') }}">{{ __('interface.browse_categories') }}</div>
    <div class="view-current-cat hidden"><a class="webshop-page-link" href="#">View All</a></div>
    <span class="webshop-close-mobile-menu">&#10005;</span>
    <div class="webshop-mobile-top-category-container">
        @foreach ($categories as $category)
            <div class="webshop-mobile-top-item webshop-mobile-top-category-{{$category['category_uid']}}
                {{$topLevelCategory && $topLevelCategory->category_uid == $category['category_uid'] ? 'selected' : ''}}" 
                 data-category_uid="{{$category['category_uid']}}"
            >
                <a href="{{url('/products/?category='.$category['category_uid'])}}"
                   data-embed_url="{{url('/embed/products/?category='.$category['category_uid'])}}"
                   data-category_uid="{{$category['category_uid']}}"
                   data-category_toplevel_uid="{{$category['category_uid']}}"
                   class="webshop-mobile-top-item-link">
                    {{ $category['name'][$language] }}
                </a>
                <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M10,17a1,1,0,0,1-.707-1.707L12.586,12,9.293,8.707a1,1,0,0,1,1.414-1.414L15.414,12l-4.707,4.707A1,1,0,0,1,10,17Z"></path></svg></span>
            </div>
        @endforeach
    </div>

    @foreach ($categories as $category)
        @foreach($category['children_categories'] as $child_cat)
            <div class="webshop-sub-category webshop-mobile-sub-category-{{$child_cat['category_uid']}} webshop-mobile-parent-cat-{{$category['category_uid']}}">
                <h2>
                    <a href="{{url('/products/?category='.$child_cat['category_uid'])}}"
                       data-embed_url="{{url('/embed/products/?category='.$child_cat['category_uid'])}}"
                       data-category_uid="{{$child_cat['category_uid']}}"
                       data-category_toplevel_uid="{{$category['category_uid']}}"
                       class="webshop-mobile-second-sub {{$selectedCategory && $selectedCategory === $child_cat['category_uid'] ? 'selected' : ''}}">
                        {{ $child_cat['name'][$language] }}
                    </a>
                    <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M10,17a1,1,0,0,1-.707-1.707L12.586,12,9.293,8.707a1,1,0,0,1,1.414-1.414L15.414,12l-4.707,4.707A1,1,0,0,1,10,17Z"></path></svg></span>
                </h2>
                <div class="mobile-menu-row">
                    @include('partials.categories_menu', ['category' => $child_cat, 'parent_uid' => $category['category_uid'], 'selectedCategory' => $selectedCategory])
                </div>
            </div>

        @endforeach
    @endforeach
</div>