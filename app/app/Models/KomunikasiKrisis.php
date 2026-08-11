<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomunikasiKrisis extends Model
{
    protected $table = 'komunikasi_krisis';

    protected $fillable = ['simulation_id', 'jenis', 'judul', 'isi', 'audiens', 'status', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class);
    }

    /** Template siapan siaran pers krisis umum */
    public const TEMPLATE = [
        'siaran_pers' => "Siaran Pers #{no}\n\nTentang: {topik}\n\n{is} telah mengambil langkah ... dengan melibatkan ...\n\nDemikian disampaikan untukinformasi publik.\n\nKontak: {kontak}",
        'klarifikasi' => "Klarifikasi / Koreksi Informasi\n\nInformasi yang beredar: {informasi}\n\nFakta: {fakta}\n\nHarap publik merujuk informasi resmi di {sumber_resmi}.",
        'briefing_media' => "Briefing Media\n\n1. Situasi Saat Ini: {situasi}\n2. Tindakan yang Diambil: {tindakan}\n3. Langkah Selanjutnya: {langkah_selanjutnya}\n4. Informasi untuk Publik: {info_publik}",
    ];
}
