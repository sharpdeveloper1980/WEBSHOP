<?php $children = array_key_exists('children', $category) ? $category['children'] : $category['children_categories']; ?>
<li id="webshop-category-{{ $category['category_uid'] }}">
    <a class="webshop-category-link" data-uid="{{ $category['category_uid'] }}" href="#">
        {{ $category['name']['en'] }} ({{$category['products_count']}})
    </a>
    @if (count($children) > 0)
        <ul class="collapsed">
            @foreach($children as $children_category)
                @include('partials.categories', ['category' => $children_category])
            @endforeach
        </ul>
    @endif
</li>