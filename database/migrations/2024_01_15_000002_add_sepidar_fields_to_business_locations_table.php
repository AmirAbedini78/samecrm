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
            // کد انبار (Warehouse Code) - مشابه سپیدار
            $table->string('warehouse_code', 50)->nullable()->after('name');
            
            // مسئول انبار (Warehouse Keeper)
            $table->integer('keeper_id')->unsigned()->nullable()->after('warehouse_code');
            $table->foreign('keeper_id')->references('id')->on('users')->onDelete('set null');
            
            // نوع انبار (Warehouse Type) - مشابه سپیدار
            $table->enum('warehouse_type', ['central', 'branch', 'consignment', 'quarantine', 'waste', 'temporary'])->default('central')->after('keeper_id');
            
            // حساب معین موجودی (Inventory Account)
            $table->string('inventory_account_id', 50)->nullable()->after('warehouse_type');
            
            // حساب معین صدور (Issue Account)
            $table->string('issue_account_id', 50)->nullable()->after('inventory_account_id');
            
            // ظرفیت انبار (Warehouse Capacity)
            $table->decimal('capacity', 22, 4)->nullable()->after('issue_account_id');
            
            // واحد ظرفیت (Capacity Unit)
            $table->string('capacity_unit', 20)->default('m3')->after('capacity');
            
            // آدرس نگهداری (Storage Address)
            $table->text('storage_address')->nullable()->after('capacity_unit');
            
            // توضیحات (Description)
            $table->text('description')->nullable()->after('storage_address');
            
            // فیلدهای سفارشی (Custom Fields) - JSON
            $table->json('custom_fields')->nullable()->after('description');
            
            // Indexes
            $table->index('warehouse_code');
            $table->index('warehouse_type');
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
};
