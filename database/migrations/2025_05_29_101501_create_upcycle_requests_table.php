<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upcycle_requests', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vendor_id');
            $table->char('req_status', 1);
            $table->string('shipping_method', 20);
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->text('notes')->nullable();
            $table->integer('total_pay');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upcycle_requests');
    }
};
