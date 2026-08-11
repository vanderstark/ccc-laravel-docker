<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === Media Sosial / Sentimen Monitoring ===
        Schema::create('media_sosial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulation_id')->nullable()->constrained('simulations')->nullOnDelete();
            $table->string('platform');          // X/Twitter, Facebook, Instagram, TikTok, WA
            $table->string('jenis_konten');       // berita, rumor, hoax, seruan, info_resmi
            $table->string('judul');
            $table->text('konten');
            $table->string('sumber')->nullable(); // akun/URL
            $table->string('sentimen')->default('netral'); // positif | netral | negatif
            $table->unsignedInteger('jangkauan')->default(0); // reach/impressions
            $table->string('status')->default('aktif'); // aktif | ditangani | hoax_terkonfirmasi
            $table->json('analisis')->nullable(); // deteksi rumor/hoax
            $table->timestamps();
        });

        // === Komunikasi Krisis (siaran pers, press release) ===
        Schema::create('komunikasi_krisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulation_id')->nullable()->constrained('simulations')->nullOnDelete();
            $table->string('jenis');             // siaran_pers | briefing_media | pernyataan_pimpinan | klarifikasi
            $table->string('judul');
            $table->text('isi');
            $table->string('audiens')->nullable(); // publik, media, instansi
            $table->string('status')->default('draf'); // draf | terbit | diedit
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komunikasi_krisis');
        Schema::dropIfExists('media_sosial');
    }
};
