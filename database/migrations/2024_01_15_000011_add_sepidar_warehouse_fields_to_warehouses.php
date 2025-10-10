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
        Schema::table('warehouses', function (Blueprint $table) {
            // انبار - انبار fields
            $table->decimal('beginning_period_quantity', 22, 4)->default(0)->after('description');
            $table->decimal('beginning_period_amount', 22, 4)->default(0)->after('beginning_period_quantity');
            $table->decimal('input_quantity', 22, 4)->default(0)->after('beginning_period_amount');
            $table->decimal('input_amount', 22, 4)->default(0)->after('input_quantity');
            $table->decimal('output_quantity', 22, 4)->default(0)->after('input_amount');
            $table->decimal('output_amount', 22, 4)->default(0)->after('output_quantity');
            $table->decimal('net_quantity', 22, 4)->default(0)->after('output_amount');
            $table->decimal('stock_amount', 22, 4)->default(0)->after('net_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn([
                'beginning_period_quantity',
                'beginning_period_amount',
                'input_quantity',
                'input_amount',
                'output_quantity',
                'output_amount',
                'net_quantity',
                'stock_amount'
            ]);
        });
    }
};
