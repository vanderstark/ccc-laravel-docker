<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === Peta Kurikulum Sespimmen / Sespimti ===
        Schema::create('kurikulum_levels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                 // Sespimmen, Sespimti, Sespim
            $table->string('tingkat');              // pertama | menengah | tinggi
            $table->string('deskripsi')->nullable();
            $table->unsignedInteger('durasi_hari')->default(30);
            $table->timestamps();
        });

        // Mapping skenario ke level kurikulum
        Schema::create('kurikulum_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_level_id')->constrained('kurikulum_levels')->cascadeOnDelete();
            $table->string('tipe_skenario');        // bencana | militer | siber | sosial | kepemimpinan
            $table->string('kode_skenario');        // disaster_type.code atau manual
            $table->string('nama_skenario');
            $table->unsignedInteger('jam_pelatihan')->default(4);
            $table->text('objektif')->nullable();
            $table->timestamps();
        });

        // Progress peserta per level
        Schema::create('kurikulum_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kurikulum_level_id')->constrained('kurikulum_levels')->cascadeOnDelete();
            $table->foreignId('kurikulum_mapping_id')->nullable()->constrained('kurikulum_mappings')->nullOnDelete();
            $table->foreignId('leadership_assessment_id')->nullable()->constrained('leadership_assessments')->nullOnDelete();
            $table->string('status')->default('belum'); // belum | berlangsung | selesai
            $table->decimal('skor', 5, 2)->nullable();
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurikulum_progress');
        Schema::dropIfExists('kurikulum_mappings');
        Schema::dropIfExists('kurikulum_levels');
    }
};
