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
        Schema::table('Ve', function (Blueprint $table) {
            if (!Schema::hasColumn('Ve', 'trangthai_don')) {
                $table->string('trangthai_don', 20)->default('chua_don')->after('maghe');
            }
            if (!Schema::hasColumn('Ve', 'thoidiem_don')) {
                $table->dateTime('thoidiem_don')->nullable()->after('trangthai_don');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Ve', function (Blueprint $table) {
            if (Schema::hasColumn('Ve', 'thoidiem_don')) {
                $table->dropColumn('thoidiem_don');
            }
            if (Schema::hasColumn('Ve', 'trangthai_don')) {
                $table->dropColumn('trangthai_don');
            }
        });
    }
};

