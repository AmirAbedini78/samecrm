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
        Schema::table('products', function (Blueprint $table) {
            // انبار - اقلام fields
            $table->integer('row_number')->nullable()->after('custom_fields');
            $table->date('date')->nullable()->after('row_number');
            $table->string('document_number', 50)->nullable()->after('date');
            $table->string('document_type', 50)->nullable()->after('document_number');
            $table->string('base_document_number', 50)->nullable()->after('document_type');
            $table->string('tracking_number', 100)->nullable()->after('base_document_number');
            $table->decimal('quantity', 22, 4)->default(0)->after('tracking_number');
            $table->decimal('warehouse_stock', 22, 4)->default(0)->after('quantity');
            $table->decimal('unit_price', 22, 4)->default(0)->after('warehouse_stock');
            $table->decimal('final_unit_price', 22, 4)->default(0)->after('unit_price');
            $table->decimal('final_amount', 22, 4)->default(0)->after('final_unit_price');
            $table->decimal('duties', 22, 4)->default(0)->after('final_amount');
            $table->decimal('shipping_cost', 22, 4)->default(0)->after('duties');
            $table->decimal('shipping_tax', 22, 4)->default(0)->after('shipping_cost');
            $table->decimal('net_amount', 22, 4)->default(0)->after('shipping_tax');
            $table->decimal('exchange_rate', 22, 6)->default(1)->after('net_amount');
            $table->decimal('currency_amount', 22, 4)->default(0)->after('exchange_rate');
            $table->decimal('currency_tax', 22, 4)->default(0)->after('currency_amount');
            $table->decimal('currency_duties', 22, 4)->default(0)->after('currency_tax');
            $table->decimal('agreed_return_price', 22, 4)->default(0)->after('currency_duties');
            $table->decimal('agreed_return_amount', 22, 4)->default(0)->after('agreed_return_price');
            $table->decimal('net_agreed_return', 22, 4)->default(0)->after('agreed_return_amount');
            $table->string('month', 20)->nullable()->after('net_agreed_return');
            $table->text('description')->nullable()->after('month');
            
            // Indexes
            $table->index('document_number');
            $table->index('date');
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['document_number']);
            $table->dropIndex(['date']);
            $table->dropIndex(['tracking_number']);
            
            $table->dropColumn([
                'row_number',
                'date',
                'document_number',
                'document_type',
                'base_document_number',
                'tracking_number',
                'quantity',
                'warehouse_stock',
                'unit_price',
                'final_unit_price',
                'final_amount',
                'duties',
                'shipping_cost',
                'shipping_tax',
                'net_amount',
                'exchange_rate',
                'currency_amount',
                'currency_tax',
                'currency_duties',
                'agreed_return_price',
                'agreed_return_amount',
                'net_agreed_return',
                'month',
                'description'
            ]);
        });
    }
};
