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
        Schema::create('Loaixe', function (Blueprint $table) {
            $table->string('maloai', 3)->primary();
            $table->string('tenloai', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('soghe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Loaixe');
    }
};
