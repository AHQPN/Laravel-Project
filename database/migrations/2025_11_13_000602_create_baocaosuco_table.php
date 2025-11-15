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
        if (!Schema::hasTable('BaocaoSuco')) {
            Schema::create('BaocaoSuco', function (Blueprint $table) {
                $table->id('id_baocao');
                $table->string('machuyendi', 15);
                $table->string('manv', 5);
                $table->string('loai_suco', 100);
                $table->text('mota')->nullable();
                $table->string('duongdan_anh')->nullable();
                $table->string('trangthai', 20)->default('moi_tao');
                $table->timestamp('tao_luc')->useCurrent();
                $table->timestamp('capnhat_luc')->nullable();

                $table->foreign('machuyendi')->references('machuyendi')->on('Chuyendi');
                $table->foreign('manv')->references('manv')->on('Nhanvien');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BaocaoSuco');
    }
};

