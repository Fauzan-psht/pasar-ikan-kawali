<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('restrict');
            $table->enum('tipe', [
                'masuk', 'keluar', 'void', 'koreksi', 'susut', 'reservasi', 'release',
            ]);
            $table->decimal('qty', 10, 2);
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('alasan')->nullable();
            $table->timestamps();

            $table->index(['produk_id', 'tipe']);
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_movements');
    }
};