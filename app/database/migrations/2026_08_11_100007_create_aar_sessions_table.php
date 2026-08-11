<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aar_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('simulation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('leadership_assessment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tahap'); // briefing | simulation | decision | aar | feedback
            $table->string('judul');
            $table->text('konten')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aar_sessions');
    }
};
