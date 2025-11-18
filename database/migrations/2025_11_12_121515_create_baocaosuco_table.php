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
        Schema::create('baocaosuco', function (Blueprint $table) {
            $table->id('id_baocao');
            $table->string('machuyendi', 15)->nullable();
            $table->string('manv', 5)->nullable();
            $table->string('loai_suco', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('mota')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('duongdan_anh', 255)->nullable();
            $table->string('trangthai', 20)->default('moi_tao');
            $table->timestamp('tao_luc')->nullable();
            $table->timestamp('capnhat_luc')->nullable();
            
            $table->foreign('machuyendi')->references('machuyendi')->on('chuyendi')->onDelete('cascade');
            $table->foreign('manv')->references('manv')->on('nhanvien')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baocaosuco');
    }
};
