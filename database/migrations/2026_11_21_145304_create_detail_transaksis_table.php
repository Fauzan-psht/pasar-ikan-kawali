<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produks')->nullOnDelete();
            $table->string('nama_snapshot');
            $table->decimal('qty', 10, 2);
            $table->enum('satuan_snapshot', ['kg', 'ekor']);
            $table->decimal('harga_snapshot', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};