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
        Schema::create('khach', function (Blueprint $table) {
            $table->string('makh', 10)->primary();
            $table->string('password', 255)->nullable();
            $table->string('ten', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('sdt', 15)->nullable();
            $table->string('diachi', 200)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->date('ngaysinh')->nullable();
            $table->string('gioitinh', 10)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach');
    }
};
