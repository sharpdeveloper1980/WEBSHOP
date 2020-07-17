<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'de')->get()->pluck('value', 'key')->toArray();