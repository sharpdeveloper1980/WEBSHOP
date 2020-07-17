<?php

namespace App\Nova;

use App\Localization;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use OptimistDigital\MultiselectField\Multiselect;

class Domain extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Domain';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'domain';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'domain',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $stores = \App\Store::all()->pluck('name', 'id');
        return [
            Text::make('Domain Name', 'domain')
                ->sortable()
                ->rules('required', 'max:255'),
            
            Select::make('Language')
                ->options(Localization::$languages)
                ->rules('max:255'),
            
            Multiselect::make('Stores', 'store_ids')
                ->options($stores)
                ->saveAsJSON(true),

            Image::make('Hero Image', 'hero_img_url'),
            
            Text::make('Hero Image Link', 'hero_img_link')
                ->sortable()
                ->rules('max:255'),

            Image::make('Logo Image', 'logo_img_url'),

            Code::make('Commercial HTML', 'commercial_html')
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
