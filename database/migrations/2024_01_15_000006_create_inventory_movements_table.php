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
        Schema::create('inventory_movements', function (Blueprint $table) {
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
            
            // سند انبار (Inventory Document)
            $table->integer('inventory_document_id')->unsigned()->nullable();
            $table->foreign('inventory_document_id')->references('id')->on('inventory_documents')->onDelete('cascade');
            
            // شماره سریال (Serial Number)
            $table->string('serial_number', 100)->nullable();
            
            // شماره پارت (Batch Number)
            $table->string('batch_number', 100)->nullable();
            
            // تاریخ انقضا (Expiry Date)
            $table->date('expiry_date')->nullable();
            
            // تاریخ (Date)
            $table->date('movement_date');
            
            // شماره (Number)
            $table->string('movement_number', 50);
            
            // سند (Document)
            $table->string('document_type', 50);
            
            // نوع (Type)
            $table->string('movement_type', 50);
            
            // شماره سند مبنا (Base Document Number)
            $table->string('base_document_number', 100)->nullable();
            
            // ردیابی (Tracking)
            $table->string('tracking_number', 100)->nullable();
            
            // مقدار (Quantity)
            $table->decimal('quantity', 22, 4);
            
            // موجودی انبار فی (Warehouse Balance Unit)
            $table->decimal('warehouse_balance_unit', 22, 4)->default(0);
            
            // فی تمام شده (Total Cost)
            $table->decimal('total_cost', 22, 4)->default(0);
            
            // قیمت واحد (Unit Price)
            $table->decimal('unit_price', 22, 4)->default(0);
            
            // مبلغ کل (Total Amount)
            $table->decimal('total_amount', 22, 4)->default(0);
            
            // مالیات (Tax)
            $table->decimal('tax_amount', 22, 4)->default(0);
            
            // عوارض (Duties)
            $table->decimal('duties_amount', 22, 4)->default(0);
            
            // هزینه حمل (Shipping Cost)
            $table->decimal('shipping_cost', 22, 4)->default(0);
            
            // مالیات حمل (Shipping Tax)
            $table->decimal('shipping_tax', 22, 4)->default(0);
            
            // عوارض حمل (Shipping Duties)
            $table->decimal('shipping_duties', 22, 4)->default(0);
            
            // مبلغ خالص (Net Amount)
            $table->decimal('net_amount', 22, 4)->default(0);
            
            // ارز (Currency)
            $table->string('currency', 3)->default('USD');
            
            // نرخ ارز (Exchange Rate)
            $table->decimal('exchange_rate', 22, 4)->default(1);
            
            // مبلغ ارز (Currency Amount)
            $table->decimal('currency_amount', 22, 4)->default(0);
            
            // مالیات ارز (Currency Tax)
            $table->decimal('currency_tax', 22, 4)->default(0);
            
            // عوارض ارز (Currency Duties)
            $table->decimal('currency_duties', 22, 4)->default(0);
            
            // توضیحات (Description)
            $table->text('description')->nullable();
            
            // ایجادکننده (Created By)
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes
            $table->index('business_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('location_id');
            $table->index('inventory_document_id');
            $table->index('serial_number');
            $table->index('batch_number');
            $table->index('expiry_date');
            $table->index('movement_date');
            $table->index('movement_number');
            $table->index('document_type');
            $table->index('movement_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
};
