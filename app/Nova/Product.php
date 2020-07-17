<?php

namespace App\Nova;

use Halimtuhu\ArrayImages\ArrayImages;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Product';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'product_name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Image::make('Photo')
                ->thumbnail(function () {
                    return $this->photo ? $this->photo : null;
                })
                ->preview(function () {
                    return $this->photo ? $this->photo : null;
                }),

            Text::make('Name', 'name')
                ->sortable()
                ->readonly(true),

            Code::make('Product Name Localization', 'product_name')
                ->json(),

            Code::make('Description Localization', 'description')
                ->json(),

            Number::make('Quantity')
                ->sortable(),

            Currency::make('Price')
                ->sortable(),

            Number::make('Currency'),

            Number::make('Discount')
                ->sortable(),

            Text::make('Table Name', 'table_name')
                ->sortable(),

            Number::make('Status', 'status_code')
                ->sortable(),
            
            Number::make('VAT', 'vat_percentage')
                ->sortable(),

            BelongsTo::make('Store', 'store', Store::class)->hideFromIndex()->searchable(),

            BelongsToMany::make('Categories', 'productCategories', Category::class)->hideFromIndex()->searchable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
