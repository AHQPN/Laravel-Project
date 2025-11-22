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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('manv', 5); // Mã nhân viên thực hiện
            $table->string('action', 50); // created, updated, deleted, approved, cancelled
            $table->string('model', 100); // Tên model (Hoadon, Nhanvien, Chuyendi, etc.)
            $table->string('model_id', 20)->nullable(); // ID của record
            $table->json('old_values')->nullable(); // Giá trị cũ (cho update)
            $table->json('new_values')->nullable(); // Giá trị mới
            $table->string('ip_address', 45); // IP address
            $table->text('user_agent')->nullable(); // Browser info
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->timestamps();
            
            // Foreign key
            $table->foreign('manv')->references('manv')->on('nhanvien')->onDelete('cascade');
            
            // Indexes
            $table->index(['manv', 'created_at']);
            $table->index(['model', 'model_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
