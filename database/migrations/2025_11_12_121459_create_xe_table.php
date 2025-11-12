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
        Schema::create('xe', function (Blueprint $table) {
            $table->string('maxe', 5)->primary();
            $table->string('maloai', 3)->nullable();
            $table->string('soxe', 10)->nullable();
            $table->string('manv', 5)->nullable();
            
            $table->foreign('maloai')->references('maloai')->on('loaixe');
            $table->foreign('manv')->references('manv')->on('nhanvien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xe');
    }
};
