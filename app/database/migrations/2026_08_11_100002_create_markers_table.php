<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('simulation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('type', ['unit', 'incident', 'asset']);
            $table->string('nama');
            $table->string('kategori')->nullable(); // unit: dalmas, samapta, dst
            $table->decimal('lat', 10, 7);
            $table->decimal('lon', 10, 7);
            $table->string('status')->default('active'); // active | standby | on_mission
            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markers');
    }
};
