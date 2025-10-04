<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            
            // کالا (Product)
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // واریاسیون (Variation)
            $table->integer('variation_id')->unsigned()->nullable();
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            
            // انبار (Warehouse)
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('cascade');
            
            // شماره سریال (Serial Number)
            $table->string('serial_number', 100)->nullable();
            
            // شماره پارت (Batch Number)
            $table->string('batch_number', 100)->nullable();
            
            // تاریخ انقضا (Expiry Date)
            $table->date('expiry_date')->nullable();
            
            // موجودی فعلی (Current Stock)
            $table->decimal('current_stock', 22, 4)->default(0);
            
            // موجودی رزرو (Reserved Stock)
            $table->decimal('reserved_stock', 22, 4)->default(0);
            
            // موجودی قابل فروش (Available Stock)
            $table->decimal('available_stock', 22, 4)->default(0);
            
            // قیمت میانگین (Average Price)
            $table->decimal('average_price', 22, 4)->default(0);
            
            // قیمت آخرین خرید (Last Purchase Price)
            $table->decimal('last_purchase_price', 22, 4)->default(0);
            
            // قیمت آخرین فروش (Last Sales Price)
            $table->decimal('last_sales_price', 22, 4)->default(0);
            
            // مبلغ موجودی (Stock Value)
            $table->decimal('stock_value', 22, 4)->default(0);
            
            // ارز (Currency)
            $table->string('currency', 3)->default('USD');
            
            // نرخ ارز (Exchange Rate)
            $table->decimal('exchange_rate', 22, 4)->default(1);
            
            // مبلغ ارز (Currency Value)
            $table->decimal('currency_value', 22, 4)->default(0);
            
            // تاریخ آخرین ورود (Last In Date)
            $table->timestamp('last_in_date')->nullable();
            
            // تاریخ آخرین خروج (Last Out Date)
            $table->timestamp('last_out_date')->nullable();
            
            // وضعیت (Status)
            $table->enum('status', ['active', 'inactive', 'quarantine', 'waste'])->default('active');
            
            // توضیحات (Description)
            $table->text('description')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('business_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('location_id');
            $table->index('serial_number');
            $table->index('batch_number');
            $table->index('expiry_date');
            $table->index('status');
            
            // Unique constraint
            $table->unique(['product_id', 'variation_id', 'location_id', 'serial_number', 'batch_number'], 'unique_inventory_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_balances');
    }
};
