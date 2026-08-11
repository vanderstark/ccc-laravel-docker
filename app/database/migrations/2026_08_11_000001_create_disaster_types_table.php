<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel disaster_types — 31 tipe bencana & operasi militer (SKU CCC).
     */
    public function up(): void
    {
        Schema::create('disaster_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();          // kode API, mis. earthquake
            $table->string('nama');                        // nama Indonesia
            $table->string('kategori');                    // Bencana Alam Geologis, dll.
            $table->string('kelompok');                    // Alam / Non-Alam / Sosial / Militer
            $table->text('deskripsi')->nullable();
            $table->json('param_demo')->nullable();        // parameter default utk demo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_types');
    }
};