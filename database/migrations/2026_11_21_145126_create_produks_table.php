<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('tipe_produk', ['konsumsi', 'benih'])->default('konsumsi');
            $table->enum('satuan', ['kg', 'ekor'])->default('kg');
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->decimal('harga_modal', 12, 2)->default(0);
            $table->decimal('stok', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['kategori_id', 'status']);
            $table->index('tipe_produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};