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
        Schema::table('ve', function (Blueprint $table) {
            if (!Schema::hasColumn('ve', 'trangthai')) {
                $table->string('trangthai', 20)->nullable()->after('maghe')->default('Available');
            }
            if (!Schema::hasColumn('ve', 'pending_expires_at')) {
                $table->dateTime('pending_expires_at')->nullable()->after('trangthai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ve', function (Blueprint $table) {
            if (Schema::hasColumn('ve', 'pending_expires_at')) {
                $table->dropColumn('pending_expires_at');
            }
            if (Schema::hasColumn('ve', 'trangthai')) {
                $table->dropColumn('trangthai');
            }
        });
    }
};
