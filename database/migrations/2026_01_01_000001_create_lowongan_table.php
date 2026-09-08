<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('instansi');
            $table->string('bidang')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('kualifikasi')->nullable();
            $table->unsignedInteger('kuota')->default(1);
            $table->unsignedInteger('terisi')->default(0);
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
