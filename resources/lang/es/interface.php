<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'es')->get()->pluck('value', 'key')->toArray();