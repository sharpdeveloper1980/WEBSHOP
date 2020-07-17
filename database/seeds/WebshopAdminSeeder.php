<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WebshopAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_id = DB::table('users')->insertGetId([
            'name' => 'Webshop Admin',
            'email' => 'webshop.admin@webshop.test',
            'password' => Hash::make('HztqAd3cSJM3'),
        ]);
        DB::table('permissions')->insert([
            'user_id' => $admin_id,
            'name' => 'Webshop Admin',
            'is_webshop_admin' => 1,
        ]);
    }
}
