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
        Schema::table('Chuyendi', function (Blueprint $table) {
            if (!Schema::hasColumn('Chuyendi', 'trangthai')) {
                $table->string('trangthai', 20)->default('sap_chay')->after('gia');
            }

            if (!Schema::hasColumn('Chuyendi', 'batdau_luc')) {
                $table->dateTime('batdau_luc')->nullable()->after('trangthai');
            }

            if (!Schema::hasColumn('Chuyendi', 'ketthuc_luc')) {
                $table->dateTime('ketthuc_luc')->nullable()->after('batdau_luc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Chuyendi', function (Blueprint $table) {
            if (Schema::hasColumn('Chuyendi', 'ketthuc_luc')) {
                $table->dropColumn('ketthuc_luc');
            }
            if (Schema::hasColumn('Chuyendi', 'batdau_luc')) {
                $table->dropColumn('batdau_luc');
            }
            if (Schema::hasColumn('Chuyendi', 'trangthai')) {
                $table->dropColumn('trangthai');
            }
        });
    }
};

