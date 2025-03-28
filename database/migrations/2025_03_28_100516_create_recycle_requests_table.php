<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recycle_requests', function (Blueprint $table) {
            $table->string('request_id', 7)->primary();
            $table->string('vendor_id', 7);
            $table->string('customer_id', 7);
            $table->string('message_id', 7);
            $table->char('req_status', 1);
            $table->char('delivery_type', 1);
            $table->integer('total_pay');
            $table->timestamps();
        
            $table->foreign('vendor_id')->references('vendor_id')->on('vendors')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('message_id')->references('message_id')->on('messages')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycle_requests');
    }
};
