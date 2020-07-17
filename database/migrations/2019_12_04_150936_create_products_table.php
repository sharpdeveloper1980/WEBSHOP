<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id');
            $table->integer('server_id');
            $table->integer('product_number');
            $table->unsignedBigInteger('webshop_store_id');
            $table->integer('client_number');
            $table->integer('user_id');
            $table->json('product_name');
            $table->json('description');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('discount');
            $table->datetime('created')->nullable();
            $table->datetime('changed')->nullable();
            $table->string('currency');
            $table->string('table_name')->nullable();
            $table->integer('status_code');
            $table->integer('vat_percentage');
            $table->json('categories');
            $table->json('tags');
            $table->json('photos');
            $table->json('channels');
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
        Schema::dropIfExists('products');
    }
}
