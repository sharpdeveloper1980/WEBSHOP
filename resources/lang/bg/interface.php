<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'bg')->get()->pluck('value', 'key')->toArray();