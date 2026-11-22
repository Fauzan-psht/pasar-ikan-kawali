<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_masuk_id')->constrained('stok_masuks')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('restrict');
            $table->decimal('qty', 10, 2);
            $table->decimal('harga_modal', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_stok_masuks');
    }
};