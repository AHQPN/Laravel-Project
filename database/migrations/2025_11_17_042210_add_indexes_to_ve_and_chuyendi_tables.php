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
        // Add indexes to Ve table for better query performance
        Schema::table('Ve', function (Blueprint $table) {
            // Composite index for filtering seats by trip and status
            $table->index(['machuyendi', 'trangthai'], 'idx_ve_machuyendi_trangthai');
            
            // Index for pending expiration queries
            $table->index(['trangthai', 'pending_expires_at'], 'idx_ve_trangthai_pending');
            
            // Index for pickup status filtering
            $table->index('trangthai_don', 'idx_ve_trangthai_don');
        });

        // Add index to Chuyendi table for status filtering
        Schema::table('Chuyendi', function (Blueprint $table) {
            $table->index('trangthai', 'idx_chuyendi_trangthai');
            
            // Index for date-based queries
            $table->index('thoigiandi', 'idx_chuyendi_thoigiandi');
            
            // Index for trip status tracking
            $table->index(['trangthai', 'batdau_luc'], 'idx_chuyendi_status_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Ve', function (Blueprint $table) {
            $table->dropIndex('idx_ve_machuyendi_trangthai');
            $table->dropIndex('idx_ve_trangthai_pending');
            $table->dropIndex('idx_ve_trangthai_don');
        });

        Schema::table('Chuyendi', function (Blueprint $table) {
            $table->dropIndex('idx_chuyendi_trangthai');
            $table->dropIndex('idx_chuyendi_thoigiandi');
            $table->dropIndex('idx_chuyendi_status_start');
        });
    }
};
