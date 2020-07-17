<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'fr')->get()->pluck('value', 'key')->toArray();