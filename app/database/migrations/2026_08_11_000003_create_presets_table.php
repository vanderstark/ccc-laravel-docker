<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();      // natuna / papua / timor
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('lat', 10, 6);
            $table->decimal('lon', 10, 6);
            $table->integer('zoom')->default(10);
            $table->integer('population')->default(500000);
            $table->decimal('area_km2', 10, 2)->default(50.00);
            $table->json('disaster_types')->nullable(); // tipe bencana yang relevan
            $table->json('param_overrides')->nullable(); // parameter preset
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};