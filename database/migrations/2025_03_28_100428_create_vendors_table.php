<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->text('address');
            $table->string('phone_number', 15);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('vendors');
    }
};
