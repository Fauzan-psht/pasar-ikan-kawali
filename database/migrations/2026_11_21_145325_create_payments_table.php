<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->nullOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->string('provider')->default('manual');
            $table->string('provider_ref')->nullable()->unique();
            $table->enum('metode', ['tunai', 'qris', 'transfer', 'va'])->default('tunai');
            $table->decimal('nominal', 14, 2);
            $table->enum('status', [
                'pending', 'paid', 'failed', 'expired',
                'refunded', 'partially_refunded',
            ])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['status', 'metode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};