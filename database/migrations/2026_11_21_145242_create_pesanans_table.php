<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_phone', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('metode_pengambilan', ['ambil', 'kirim'])->default('ambil');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->enum('status', [
                'pending_payment', 'confirmed', 'processing',
                'ready', 'completed', 'cancelled', 'expired',
            ])->default('pending_payment');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('guest_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};