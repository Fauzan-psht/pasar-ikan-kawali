<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->nullable()->constrained('produks')->nullOnDelete();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->nullOnDelete();
            $table->string('nama');
            $table->string('telepon', 30)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->index(['produk_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};