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
        Schema::table('business_locations', function (Blueprint $table) {
            // Remove warehouse fields from business_locations
            $table->dropIndex(['warehouse_code']);
            $table->dropIndex(['warehouse_type']);
            
            $table->dropForeign(['keeper_id']);
            $table->dropColumn([
                'warehouse_code',
                'keeper_id',
                'warehouse_type',
                'inventory_account_id',
                'issue_account_id',
                'capacity',
                'capacity_unit',
                'storage_address',
                'description',
                'custom_fields'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_locations', function (Blueprint $table) {
            // Re-add warehouse fields if needed
            $table->string('warehouse_code', 50)->nullable()->after('name');
            $table->integer('keeper_id')->unsigned()->nullable()->after('warehouse_code');
            $table->foreign('keeper_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('warehouse_type', ['central', 'branch', 'consignment', 'quarantine', 'waste', 'temporary'])->default('central')->after('keeper_id');
            $table->string('inventory_account_id', 50)->nullable()->after('warehouse_type');
            $table->string('issue_account_id', 50)->nullable()->after('inventory_account_id');
            $table->decimal('capacity', 22, 4)->nullable()->after('issue_account_id');
            $table->string('capacity_unit', 20)->default('m3')->after('capacity');
            $table->text('storage_address')->nullable()->after('capacity_unit');
            $table->text('description')->nullable()->after('storage_address');
            $table->json('custom_fields')->nullable()->after('description');
            
            $table->index('warehouse_code');
            $table->index('warehouse_type');
        });
    }
};
