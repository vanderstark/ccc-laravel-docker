<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('disaster_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('war_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('preset_id')->nullable()->constrained()->nullOnDelete();

            // Input dasar
            $table->string('location')->default('Kota Semarang');
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 10, 6)->nullable();
            $table->integer('population')->default(500000);
            $table->decimal('area_km2', 10, 2)->default(50.00);
            $table->string('area_type', 20)->default('suburb'); // urban/rural/suburb
            $table->decimal('infrastructure_density', 3, 2)->default(0.50);

            // Parameter khusus (fleksibel, disimpan sebagai JSON)
            $table->json('params')->nullable();

            // Hasil simulasi
            $table->string('classification')->nullable();
            $table->string('alert_level')->nullable();     // hijau/kuning/oranye/merah
            $table->bigInteger('affected_population')->default(0);
            $table->bigInteger('estimated_casualties')->default(0);
            $table->bigInteger('estimated_deaths')->default(0);
            $table->bigInteger('estimated_injured')->default(0);
            $table->bigInteger('displaced')->default(0);
            $table->bigInteger('damaged_buildings')->default(0);
            $table->bigInteger('destroyed_buildings')->default(0);
            $table->decimal('economic_damage_usd', 18, 2)->default(0);

            $table->json('impact_detail')->nullable();     // detail dampak per tipe
            $table->json('resources')->nullable();         // estimasi sumber daya
            $table->json('actions')->nullable();           // rekomendasi tindakan 4 fase

            $table->timestamps();

            $table->index(['disaster_type_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};