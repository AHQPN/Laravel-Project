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
        Schema::create('hoadon', function (Blueprint $table) {
            $table->string('mahd', 10)->primary();
            $table->dateTime('thoigian')->nullable();
            $table->string('makh', 10)->nullable();
            $table->string('manv', 5)->nullable();
            $table->string('matt', 2)->nullable();
            $table->integer('soluong')->nullable();
            $table->integer('thanhtien')->nullable();
            $table->string('trangthai', 20)->default('Chờ duyệt');
            
            $table->foreign('makh')->references('makh')->on('khach');
            $table->foreign('manv')->references('manv')->on('nhanvien');
            $table->foreign('matt')->references('matt')->on('thanhtoan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoadon');
    }
};
