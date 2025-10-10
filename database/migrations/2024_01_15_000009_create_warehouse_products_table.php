<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('product_id');
            $table->decimal('quantity', 22, 4)->default(0);
            $table->decimal('min_stock', 22, 4)->default(0);
            $table->decimal('max_stock', 22, 4)->default(0);
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['warehouse_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_products');
    }
};
