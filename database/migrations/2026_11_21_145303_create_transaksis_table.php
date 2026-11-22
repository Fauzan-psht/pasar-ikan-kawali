<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->nullOnDelete();
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->nullOnDelete();
            $table->enum('kanal', ['pos', 'online'])->default('pos');
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('bayar', 14, 2)->default(0);
            $table->decimal('kembalian', 14, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'void', 'refunded'])->default('paid');
            $table->text('void_reason')->nullable();
            $table->timestamp('void_at')->nullable();
            $table->foreignId('void_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['kanal', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};