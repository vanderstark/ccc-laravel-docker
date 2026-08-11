<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leadership_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('simulation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('war_id')->nullable()->constrained()->nullOnDelete();
            $table->string('scenario_type'); // bencana | militer | kepemimpinan
            $table->string('scenario_name');
            $table->decimal('skor_keputusan', 5, 2)->default(0);      // kualitas keputusan
            $table->decimal('skor_kecepatan', 5, 2)->default(0);      // kecepatan respons
            $table->decimal('skor_kolaborasi', 5, 2)->default(0);     // kolaborasi lintas fungsi
            $table->decimal('skor_komunikasi', 5, 2)->default(0);     // komunikasi krisis
            $table->decimal('skor_integritas', 5, 2)->default(0);     // integritas
            $table->decimal('skor_risiko', 5, 2)->default(0);         // kemampuan mengelola risiko
            $table->decimal('skor_total', 5, 2)->default(0);          // rata-rata (0-100)
            $table->string('grade')->default('C');                    // A/B/C/D/E
            $table->text('catatan')->nullable();
            $table->json('detail_penilaian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_assessments');
    }
};
