<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'posted'])->default('draft');
            $table->timestamps();

            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};