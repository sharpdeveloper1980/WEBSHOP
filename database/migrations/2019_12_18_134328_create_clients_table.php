<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('server_id');
            $table->integer('user_id');
            $table->string('client_number');
            $table->boolean('is_active');
            $table->unsignedBigInteger('webshop_store_id');
            $table->unsignedBigInteger('webshop_user_id');
            $table->boolean('is_anonymous')->nullable();
            $table->string('nickname')->nullable();
            $table->string('website_url')->nullable();
            $table->string('street')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_business_client')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_id')->nullable();
            $table->string('seller_picture_url')->nullable();
            $table->json('seller_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
