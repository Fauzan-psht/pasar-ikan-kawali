<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_pasar', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->default('Pasar Ikan Kawali');
            $table->text('deskripsi')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('jam_buka')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('maps_url')->nullable();
            $table->text('maps_embed')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_pasar');
    }
};