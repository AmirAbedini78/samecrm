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
        Schema::create('inventory_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            
            // شماره سند (Document Number) - مشابه سپیدار
            $table->string('document_number', 50);
            
            // نوع سند (Document Type) - مشابه سپیدار
            $table->enum('document_type', ['receipt', 'delivery', 'transfer', 'adjustment', 'return_receipt', 'return_delivery'])->default('receipt');
            
            // تاریخ سند (Document Date)
            $table->date('document_date');
            
            // انبار مبدأ (Source Warehouse)
            $table->integer('source_location_id')->unsigned()->nullable();
            $table->foreign('source_location_id')->references('id')->on('business_locations')->onDelete('cascade');
            
            // انبار مقصد (Destination Warehouse)
            $table->integer('destination_location_id')->unsigned()->nullable();
            $table->foreign('destination_location_id')->references('id')->on('business_locations')->onDelete('cascade');
            
            // طرف حساب (Contact)
            $table->integer('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            
            // سفارش مرجع (Reference Order)
            $table->string('reference_order', 100)->nullable();
            
            // شماره سند مبنا (Base Document Number)
            $table->string('base_document_number', 100)->nullable();
            
            // وضعیت تأیید (Approval Status)
            $table->enum('status', ['draft', 'approved', 'rejected', 'cancelled'])->default('draft');
            
            // تأییدکننده (Approved By)
            $table->integer('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // تاریخ تأیید (Approval Date)
            $table->timestamp('approved_at')->nullable();
            
            // توضیحات (Description)
            $table->text('description')->nullable();
            
            // یادداشت (Notes)
            $table->text('notes')->nullable();
            
            // هزینه حمل (Shipping Cost)
            $table->decimal('shipping_cost', 22, 4)->default(0);
            
            // ارز (Currency)
            $table->string('currency', 3)->default('USD');
            
            // نرخ ارز (Exchange Rate)
            $table->decimal('exchange_rate', 22, 4)->default(1);
            
            // مبلغ کل (Total Amount)
            $table->decimal('total_amount', 22, 4)->default(0);
            
            // ایجادکننده (Created By)
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes
            $table->index('business_id');
            $table->index('document_number');
            $table->index('document_type');
            $table->index('document_date');
            $table->index('status');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_documents');
    }
};
