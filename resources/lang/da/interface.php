<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'da')->get()->pluck('value', 'key')->toArray();