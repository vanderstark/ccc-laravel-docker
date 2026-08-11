<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wars', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tahun');
            $table->text('wilayah');
            $table->string('matra');        // darat / laut / udara / gabungan
            $table->string('kategori');     // Perang Era Kerajaan, dll.
            $table->bigInteger('pop')->default(0);
            $table->decimal('lat', 10, 6);
            $table->decimal('lon', 10, 6);
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wars');
    }
};