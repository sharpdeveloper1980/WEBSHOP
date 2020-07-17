<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'language',
    ];
    
    protected $table = 'localization';

    public static $languages = [
        'ar' => 'Arabic',
        'bg' => 'Bulgarian',
        'da' => 'Danish',
        'de' => 'German',
        'en' => 'English',
        'es' => 'Spanish',
        'fi' => 'Finnish',
        'fr' => 'French',
        'he' => 'Hebrew',
        'it' => 'Italian',
        'nl' => 'Dutch',
        'no' => 'Norwegian',
        'ru' => 'Russian',
        'sv' => 'Swedish'
    ];
}
