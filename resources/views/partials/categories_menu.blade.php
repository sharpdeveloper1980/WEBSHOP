<?php 

$children = [];
if(array_key_exists('children', $category)) {
    $children = $category['children'];
} else if(array_key_exists('children_categories', $category)) {
    $children = $category['children_categories'];
}

?>

@if (count($children) > 0)
    @foreach($children as $child)
        <div class="column">
            <h3>
                <a href="{{url('/products/?category='.$child['category_uid'])}}" 
                   data-embed_url="{{url('/embed/products/?category='.$child['category_uid'])}}"
                   data-category_uid="{{$child['category_uid']}}"
                   data-category_toplevel_uid="{{$parent_uid}}"
                   class="webshop-page-link {{$selectedCategory && $selectedCategory === $child['category_uid'] ? 'selected' : ''}}">
                    {{ !empty($child['name'][$language]) ? $child['name'][$language] : $child['name']['en'] }}
                </a>
            </h3>
        </div>
    @endforeach
@endif
