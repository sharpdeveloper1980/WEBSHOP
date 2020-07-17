<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'he')->get()->pluck('value', 'key')->toArray();