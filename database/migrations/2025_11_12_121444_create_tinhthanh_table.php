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
        Schema::create('TinhThanh', function (Blueprint $table) {
            $table->string('matinh', 4)->primary();
            $table->string('ten', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TinhThanh');
    }
};
