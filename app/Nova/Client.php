<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;

class Client extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Client';

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
        'id', 'name', 'email',
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

            Avatar::make('Logo', 'logo_url')
                ->thumbnail(function () {
                    return $this->logo_url ? $this->logo_url : null;
                })
                ->preview(function () {
                    return $this->logo_url ? $this->logo_url : null;
                }),

            Boolean::make('Anonymous', 'is_anonymous')
                ->sortable(),

            Text::make('Nickname')
                ->sortable(),

            Text::make('Website', 'website_url')
                ->sortable(),

            Country::make('Country'),

            Text::make('City')
                ->sortable(),

            Text::make('Street')
                ->sortable(),

            Text::make('Zip')
                ->sortable(),

            Boolean::make('Business Client', 'is_business_client')
                ->sortable(),

            Text::make('Business Name', 'business_name')
                ->sortable(),

            Code::make('Seller description', 'seller_description')
                ->json(),

            BelongsTo::make('Store', 'store', Store::class)->hideFromIndex()->searchable(),
            
            BelongsToMany::make('Products', 'products', Product::class)->hideFromIndex()->searchable(),
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
