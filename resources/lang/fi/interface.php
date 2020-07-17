<?php

use Illuminate\Support\Facades\DB;

return DB::table('localization')->where('language', 'fi')->get()->pluck('value', 'key')->toArray();