<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'ar')->get()->pluck('value', 'key')->toArray();