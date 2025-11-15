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
        Schema::create('CTHD', function (Blueprint $table) {
            $table->string('mahd', 10);
            $table->string('mave', 10);
            $table->integer('dongia')->nullable();
            
            $table->primary(['mahd', 'mave']);
            $table->foreign('mahd')->references('mahd')->on('Hoadon');
            $table->foreign('mave')->references('mave')->on('Ve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CTHD');
    }
};
