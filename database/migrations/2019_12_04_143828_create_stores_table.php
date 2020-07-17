<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('company_id');
            $table->integer('server_id');
            $table->string('name');
            $table->string('technical_name')->unique();
            $table->boolean('company_visible')->nullable();
            $table->text('company_description')->nullable();
            $table->text('company_contact_info')->nullable();
            $table->string('company_coordinates')->nullable();
            $table->text('company_business_hours')->nullable();
            $table->string('company_website_url')->nullable();
            $table->string('company_email')->nullable();
            $table->string('localization')->nullable();
            $table->string('territory')->nullable();
            $table->string('language')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('allow_guest_spectate')->nullable();
            $table->boolean('allow_client_registration')->nullable();
            $table->boolean('allow_client_login')->nullable();
            $table->boolean('allow_client_reservation')->nullable();
            $table->boolean('allow_client_product_marketing')->nullable();
            $table->boolean('product_pricing_enabled')->nullable();
            $table->boolean('sales_view_enabled')->nullable();
            $table->boolean('allow_webshop_product_pricing')->nullable();
            $table->boolean('use_the_term_product_feed_instead_of_webshop')->nullable();
            $table->boolean('product_pricing_1_by_1')->nullable();
            $table->boolean('mobile_app_show_product_recognition_button')->nullable();
            $table->string('logo_url')->nullable();
            $table->json('coordinates')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
