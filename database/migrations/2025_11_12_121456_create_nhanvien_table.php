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
        Schema::create('nhanvien', function (Blueprint $table) {
            $table->string('manv', 5)->primary();
            $table->string('macv', 3)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('ten', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('sdt', 15)->nullable();
            $table->string('diachi', 200)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('cccd', 12)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('ngaysinh')->nullable();
            $table->string('gioitinh', 10)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('hinhanh', 255)->nullable();
            $table->boolean('trangthai')->default(1);
            
            $table->foreign('macv')->references('macv')->on('chucvu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhanvien');
    }
};
