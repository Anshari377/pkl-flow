<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_berkas', ['cv', 'surat_pengantar', 'transkip_nilai']);
            $table->string('file_path')->nullable();
            $table->enum('status', [
                'belum_diunggah', 'menunggu_verifikasi', 'diterima', 'ditolak',
            ])->default('belum_diunggah');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'jenis_berkas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};