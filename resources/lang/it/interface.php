<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'it')->get()->pluck('value', 'key')->toArray();