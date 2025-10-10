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
        if (!Schema::hasTable('warehouses')) {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            
            // Basic Information
            $table->string('name', 256);
            $table->string('warehouse_code', 50)->unique();
            $table->enum('warehouse_type', ['central', 'branch', 'consignment', 'quarantine', 'waste', 'temporary'])->default('central');
            
            // Warehouse Keeper
            $table->integer('keeper_id')->unsigned()->nullable();
            $table->foreign('keeper_id')->references('id')->on('users')->onDelete('set null');
            
            // Capacity
            $table->decimal('capacity', 22, 4)->nullable();
            $table->string('capacity_unit', 20)->default('m3');
            
            // Accounting
            $table->string('inventory_account_id', 50)->nullable();
            $table->string('issue_account_id', 50)->nullable();
            
            // Address
            $table->text('storage_address')->nullable();
            $table->text('description')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('business_id');
            $table->index('warehouse_code');
            $table->index('warehouse_type');
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};
