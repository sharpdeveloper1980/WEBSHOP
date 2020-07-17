<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'no')->get()->pluck('value', 'key')->toArray();