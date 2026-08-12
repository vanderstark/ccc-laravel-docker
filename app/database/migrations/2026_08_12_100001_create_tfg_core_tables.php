<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== 1. SESSIONS (Menu Latihan) =====
        // State: draft → briefing → running → paused → ended
        Schema::create('exercise_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->foreignId('simulation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('preset_id')->nullable()->constrained('presets')->nullOnDelete();
            $table->string('status')->default('draft'); // draft|briefing|running|paused|ended
            $table->text('objectives')->nullable();     // 1-3 SMART objectives
            $table->text('roe')->nullable();            // Rules of Engagement
            $table->integer('durasi_menit')->default(120);
            $table->integer('t_plus_detik')->default(0); // T+ timer
            $table->timestamp('mulai_pada')->nullable();
            $table->timestamp('akhir_pada')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ===== 2. EXCON INJECTS (Menu EXCON) =====
        Schema::create('injects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('kode')->unique();           // INJ-01
            $table->string('title');
            $table->text('message');
            $table->string('visible_to')->default('all'); // all|ai|reserse|brimob|lantas|sabhara|binmas|manajemen_konflik|excon
            $table->integer('t_plus_sec')->default(0);  // when to inject
            $table->string('map_effect')->nullable();   // add_marker|add_zone|move_unit
            $table->json('map_effect_data')->nullable();
            $table->string('requires_action')->nullable(); // reserse;brimob
            $table->string('fail_effect')->nullable();  // "-20 poin"
            $table->string('status')->default('queued'); // queued|delivered|resolved|skipped
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // ===== 3. FOG OF WAR (Menu EXCON - filtering) =====
        Schema::create('fog_of_war', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('satker');                    // ai|reserse|brimob|...
            $table->string('layer')->default('situasi'); // peta|situasi|operasi
            $table->boolean('enabled')->default(true);   // true = informasi disembunyikan
            $table->json('visibility_scope')->nullable(); // list marker/zone yang visible
            $table->timestamps();
        });

        // ===== 4. ORBAT (Order of Battle) =====
        Schema::create('orbat_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('satker');                    // ai|reserse|brimob|lantas|sabhara|binmas|manajemen_konflik
            $table->string('nama_unit');
            $table->string('jenis');                     // personel|kendaraan|aset
            $table->integer('kekuatan')->default(0);     // jumlah personel/aset
            $table->string('status')->default('siaga');  // siaga|bergerak|bertugas|pulang
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('detail')->nullable();
            $table->timestamps();
        });

        // ===== 5. ORDER BOARD (Menu Operasi) =====
        Schema::create('order_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('nomor');                     // OP-001
            $table->string('jenis');                     // perintah|informasi|instruksi
            $table->string('tujuan_satker')->nullable(); // all|reserse;brimob
            $table->text('isi');
            $table->string('status')->default('draft');  // draft|dikirim|dibaca|dilaksanakan|selesai
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();
        });

        // ===== 6. SCENARIO PACKAGES (versioned) =====
        Schema::create('scenario_packages', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('versi')->default('v1.0');
            $table->string('kode')->unique();
            $table->text('description');
            $table->json('objectives')->nullable();
            $table->json('manifest')->nullable();        // metadata lengkap
            $table->string('status')->default('draft');  // draft|active|archived
            $table->timestamps();
        });

        // ===== 7. HEATMAP / MOVEMENT LOG =====
        Schema::create('movement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('entity_type');               // unit|marker|zone
            $table->unsignedBigInteger('entity_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('t_plus_sec')->default(0);
            $table->timestamps();
        });

        // ===== 8. DECISION LOG (Menu Latihan - log keputusan) =====
        Schema::create('decision_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('satker')->nullable();
            $table->text('keputusan');
            $table->string('pic');                       // Person In Charge
            $table->integer('t_plus_sec')->default(0);
            $table->timestamps();
        });

        // ===== 9. ROLEPLAY CHANNEL (EXCON - radio simulasi) =====
        Schema::create('roleplay_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('exercise_sessions')->cascadeOnDelete();
            $table->string('nama');                      // Channel 1 - Komando
            $table->string('jenis')->default('radio');   // radio|chat
            $table->string('peserta');                   // all|ai;reserse
            $table->timestamps();
        });

        Schema::create('roleplay_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained('roleplay_channels')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('satker')->nullable();
            $table->text('pesan');
            $table->integer('t_plus_sec')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roleplay_messages');
        Schema::dropIfExists('roleplay_channels');
        Schema::dropIfExists('decision_logs');
        Schema::dropIfExists('movement_logs');
        Schema::dropIfExists('scenario_packages');
        Schema::dropIfExists('order_boards');
        Schema::dropIfExists('orbat_units');
        Schema::dropIfExists('fog_of_war');
        Schema::dropIfExists('injects');
        Schema::dropIfExists('exercise_sessions');
    }
};
