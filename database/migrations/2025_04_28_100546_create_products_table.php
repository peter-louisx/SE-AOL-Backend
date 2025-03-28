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
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id', 7)->primary();
            $table->string('name', 50);
            $table->integer('price');
            $table->integer('sold');
            $table->integer('stock');
            $table->text('description');
            $table->decimal('weight', 8, 2);
            $table->string('tag_id', 7);
            $table->string('category_id', 7);
            $table->string('brand_id', 7);
            $table->timestamps();
        
            $table->foreign('tag_id')->references('tag_id')->on('product_tags')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
