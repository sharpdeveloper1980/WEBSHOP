<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Store extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Store';

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
        'id', 'name',
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

            Image::make('Logo', 'logo_url')
                ->thumbnail(function () {
                    return $this->logo_url ? $this->logo_url : null;
                })
                ->preview(function () {
                    return $this->logo_url ? $this->logo_url : null;
                }),
            
            Number::make('Server ID', 'server_id')
                ->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Technical Name')
                ->sortable()
                ->rules('required', 'max:254'),

            Boolean::make('Visible', 'company_visible')
                ->sortable(),

            Markdown::make('Description', 'company_description')
                ->sortable(),

            Markdown::make('Contact Info', 'company_contact_info')
                ->sortable(),

            Text::make('Site', 'company_website_url')
                ->sortable(),

            Text::make('Email', 'company_email')
                ->sortable(),

            Text::make('Localization')
                ->sortable(),

            Text::make('Territory')
                ->sortable(),

            HasMany::make('Products', 'products', Product::class),

//            BelongsToMany::make('Goals', 'goals', Goal::class)->hideFromIndex()->fields(new GoalMemberFields)->searchable(),
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

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {

        $permission = $request->user()->permission;
        if($permission) {
            if($permission->is_webshop_admin) {
                return $query;
            } elseif ($permission->is_store_admin && $permission->stores_id) {
                return $query->whereIn('id', $permission->stores_id);
            } else {
                return $query->where('id', -1);
            }
        }
    }
}
