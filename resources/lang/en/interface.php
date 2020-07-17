<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'en')->get()->pluck('value', 'key')->toArray();