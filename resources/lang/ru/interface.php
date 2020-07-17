<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'ru')->get()->pluck('value', 'key')->toArray();