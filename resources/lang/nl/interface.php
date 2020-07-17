<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'nl')->get()->pluck('value', 'key')->toArray();