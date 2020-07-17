<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('server_id')->after('email')->nullable();
            $table->integer('user_id')->after('server_id')->nullable();
            $table->string('username')->after('user_id')->nullable();
            $table->string('fullname')->after('username')->nullable();
            $table->string('firstname')->after('fullname')->nullable();
            $table->string('lastname')->after('firstname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('server_id');
            $table->dropColumn('user_id');
            $table->dropColumn('username');
            $table->dropColumn('fullname');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
        });
    }
}
