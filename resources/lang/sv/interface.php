<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'sv')->get()->pluck('value', 'key')->toArray();