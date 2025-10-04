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
            // کد کالا (Product Code) - مشابه سپیدار
            $table->string('item_code', 50)->nullable()->after('sku');
            
            // نوع کالا (Item Type) - مشابه سپیدار
            $table->enum('item_type', ['raw_material', 'purchased_goods', 'sale_goods', 'semi_finished', 'service', 'asset', 'waste'])->default('sale_goods')->after('item_code');
            
            // حداقل موجودی (Minimum Stock)
            $table->decimal('min_stock', 22, 4)->default(0)->after('alert_quantity');
            
            // حداکثر موجودی (Maximum Stock)
            $table->decimal('max_stock', 22, 4)->default(0)->after('min_stock');
            
            // نقطه سفارش (Reorder Point)
            $table->decimal('reorder_point', 22, 4)->default(0)->after('max_stock');
            
            // حساب معین موجودی (Inventory Account)
            $table->string('inventory_account_id', 50)->nullable()->after('reorder_point');
            
            // حساب معین خرید (Purchase Account)
            $table->string('purchase_account_id', 50)->nullable()->after('inventory_account_id');
            
            // حساب معین فروش (Sales Account)
            $table->string('sales_account_id', 50)->nullable()->after('purchase_account_id');
            
            // محل نگهداری پیش‌فرض (Default Rack)
            $table->string('default_rack', 100)->nullable()->after('sales_account_id');
            
            // ردیف (Row)
            $table->string('default_row', 100)->nullable()->after('default_rack');
            
            // قفسه (Shelf)
            $table->string('default_shelf', 100)->nullable()->after('default_row');
            
            // ابعاد (Dimensions)
            $table->string('dimensions', 100)->nullable()->after('default_shelf');
            
            // رنگ (Color)
            $table->string('color', 50)->nullable()->after('dimensions');
            
            // مدل (Model)
            $table->string('model', 100)->nullable()->after('color');
            
            // وضعیت سریال (Serial Status)
            $table->boolean('serial_required')->default(false)->after('model');
            
            // وضعیت انقضا (Expiry Status)
            $table->boolean('expiry_required')->default(false)->after('serial_required');
            
            // وضعیت فعال/غیرفعال (Active Status)
            $table->boolean('is_active')->default(true)->after('expiry_required');
            
            // قیمت خرید پیش‌فرض (Default Purchase Price)
            $table->decimal('default_purchase_price', 22, 4)->default(0)->after('is_active');
            
            // قیمت فروش پیش‌فرض (Default Sales Price)
            $table->decimal('default_sales_price', 22, 4)->default(0)->after('default_purchase_price');
            
            // درصد سود (Profit Percentage)
            $table->decimal('profit_percentage', 5, 2)->default(0)->after('default_sales_price');
            
            // سقف تخفیف (Discount Limit)
            $table->decimal('discount_limit', 5, 2)->default(0)->after('profit_percentage');
            
            // ارز (Currency)
            $table->string('currency', 3)->default('USD')->after('discount_limit');
            
            // مشخصات فنی (Technical Specifications) - JSON
            $table->json('technical_specs')->nullable()->after('currency');
            
            // فیلدهای سفارشی (Custom Fields) - JSON
            $table->json('custom_fields')->nullable()->after('technical_specs');
            
            // Indexes
            $table->index('item_code');
            $table->index('item_type');
            $table->index('is_active');
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
            $table->dropIndex(['item_code']);
            $table->dropIndex(['item_type']);
            $table->dropIndex(['is_active']);
            
            $table->dropColumn([
                'item_code',
                'item_type',
                'min_stock',
                'max_stock',
                'reorder_point',
                'inventory_account_id',
                'purchase_account_id',
                'sales_account_id',
                'default_rack',
                'default_row',
                'default_shelf',
                'dimensions',
                'color',
                'model',
                'serial_required',
                'expiry_required',
                'is_active',
                'default_purchase_price',
                'default_sales_price',
                'profit_percentage',
                'discount_limit',
                'currency',
                'technical_specs',
                'custom_fields'
            ]);
        });
    }
};
