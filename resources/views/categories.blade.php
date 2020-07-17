<p><b>{{ __('interface.categories') }}:</b> <a href="#" class="webshop-reset-category hidden">({{ __('interface.clear') }})</a></p>
<ul class="categories">
    @foreach ($categories as $category)
        @include('partials.categories', ['category' => $category])
    @endforeach
</ul>