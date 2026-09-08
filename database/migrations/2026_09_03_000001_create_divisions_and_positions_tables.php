<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nama');
            $table->string('kategori');
            $table->string('instansi');
            $table->text('deskripsi');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('nama');
            $table->text('deskripsi');
            $table->unsignedInteger('kuota')->default(1);
            $table->unsignedInteger('terisi')->default(0);
            $table->json('kualifikasi')->nullable();
            $table->json('jurusan')->nullable();
            $table->timestamps();

            $table->unique(['division_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('divisions');
    }
};
