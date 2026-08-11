<?php
namespace App\Services;

use App\Models\LeadershipAssessment;
use App\Models\Simulation;
use App\Models\User;

/**
 * LeadershipAssessmentService — Sistem Penilaian Kepemimpinan
 * Mengukur: kualitas keputusan, kecepatan respons, kolaborasi,
 * komunikasi, integritas, dan kemampuan mengelola risiko.
 *
 * Sesuai PDF Rapat Pendirian Lab Kepemimpinan Digital Polri (poin 3d):
 * "penilaian kepemimpinan berbasis kinerja, bukan hanya pengetahuan"
 */
class LeadershipAssessmentService
{
    /** Bobot tiap dimensi (total 100). */
    public const BOBOT = [
        'keputusan'  => 25, // kualitas keputusan strategis
        'kecepatan'  => 20, // kecepatan respons
        'kolaborasi' => 15, // kolaborasi lintas fungsi/lembaga
        'komunikasi' => 15, // komunikasi krisis
        'integritas' => 10, // integritas & akuntabilitas
        'risiko'     => 15, // kemampuan mengelola risiko
    ];

    /**
     * Hitung skor otomatis dari data simulasi.
     */
    public function hitungDariSimulasi(Simulation $sim, User $user, array $ekstra = []): LeadershipAssessment
    {
        // Dimensi: skor dihitung dari karakteristik simulasi
        $keputusan  = $this->skorKeputusan($sim);
        $kecepatan  = $this->skorKecepatan($sim);
        $kolaborasi = $this->skorKolaborasi($sim, $ekstra['kolaborasi'] ?? null);
        $komunikasi = $this->skorKomunikasi($sim, $ekstra['komunikasi'] ?? null);
        $integritas = $this->skorIntegritas($ekstra['integritas'] ?? null);
        $risiko     = $this->skorRisiko($sim, $ekstra['risiko'] ?? null);

        $total = round(($keputusan + $kecepatan + $kolaborasi + $komunikasi + $integritas + $risiko) / 6, 2);
        $grade = $this->grade($total);

        return LeadershipAssessment::create([
            'user_id'          => $user->id,
            'simulation_id'    => $sim->id,
            'war_id'           => null,
            'scenario_type'    => 'bencana',
            'scenario_name'    => $sim->disasterType?->nama ?? $sim->disaster_type,
            'skor_keputusan'   => $keputusan,
            'skor_kecepatan'   => $kecepatan,
            'skor_kolaborasi'  => $kolaborasi,
            'skor_komunikasi'  => $komunikasi,
            'skor_integritas'  => $integritas,
            'skor_risiko'      => $risiko,
            'skor_total'       => $total,
            'grade'            => $grade,
            'detail_penilaian' => [
                'bobot' => self::BOBOT,
                'alert_level' => $sim->alert_level,
                'durasi_menit' => $sim->duration_minutes,
            ],
        ]);
    }

    /** Manual: nilai langsung per dimensi (0-100). */
    public function nilaiManual(User $user, array $data): LeadershipAssessment
    {
        $keputusan  = $data['skor_keputusan']  ?? 0;
        $kecepatan  = $data['skor_kecepatan']  ?? 0;
        $kolaborasi = $data['skor_kolaborasi'] ?? 0;
        $komunikasi = $data['skor_komunikasi'] ?? 0;
        $integritas = $data['skor_integritas'] ?? 0;
        $risiko     = $data['skor_risiko']     ?? 0;

        $total = round(($keputusan + $kecepatan + $kolaborasi + $komunikasi + $integritas + $risiko) / 6, 2);

        return LeadershipAssessment::create([
            'user_id'          => $user->id,
            'simulation_id'    => $data['simulation_id'] ?? null,
            'war_id'           => $data['war_id'] ?? null,
            'scenario_type'    => $data['scenario_type'] ?? 'manual',
            'scenario_name'    => $data['scenario_name'] ?? 'Manual',
            'skor_keputusan'   => $keputusan,
            'skor_kecepatan'   => $kecepatan,
            'skor_kolaborasi'  => $kolaborasi,
            'skor_komunikasi'  => $komunikasi,
            'skor_integritas'  => $integritas,
            'skor_risiko'      => $risiko,
            'skor_total'       => $total,
            'grade'            => $this->grade($total),
            'catatan'          => $data['catatan'] ?? null,
            'detail_penilaian' => ['bobot' => self::BOBOT, 'mode' => 'manual'],
        ]);
    }

    /** Perkiraan skor keputusan: kompleksitas & alert level. */
    protected function skorKeputusan(Simulation $sim): float
    {
        $base = match ($sim->alert_level) {
            'merah'  => 55,
            'oranye' => 65,
            'kuning' => 75,
            default  => 80,
        };
        // Semakin kompleks (param banyak) => peluang keputusan sulit, skor dasar lebih rendah
        $params = is_array($sim->param_demo) ? count($sim->param_demo) : 0;
        return (float) max(40, min(95, $base - $params * 0.5));
    }

    /** Kecepatan respons: makin singkat durasi per keputusan makin baik. */
    protected function skorKecepatan(Simulation $sim): float
    {
        $durasi = $sim->duration_minutes ?? 30;
        if ($durasi <= 10) return 92;
        if ($durasi <= 20) return 85;
        if ($durasi <= 30) return 75;
        if ($durasi <= 60) return 65;
        return 50;
    }

    /** Kolaborasi: default 70, bisa di-override ekstra. */
    protected function skorKolaborasi(Simulation $sim, ?float $manual = null): float
    {
        if ($manual !== null) return (float) max(0, min(100, $manual));
        // Simulasi dengan org terlibat (organization_id) => kolaborasi lebih baik
        return $sim->organization_id ? 82 : 70;
    }

    /** Komunikasi: default 72. */
    protected function skorKomunikasi(Simulation $sim, ?float $manual = null): float
    {
        if ($manual !== null) return (float) max(0, min(100, $manual));
        return 72;
    }

    /** Integritas: manual atau default 75. */
    protected function skorIntegritas(?float $manual = null): float
    {
        return (float) ($manual ?? 75);
    }

    /** Risiko: berdasar keparahan dampak. */
    protected function skorRisiko(Simulation $sim, ?float $manual = null): float
    {
        if ($manual !== null) return (float) max(0, min(100, $manual));
        // Semakin banyak korban => risiko makin besar => makin sulit dikelola
        $deaths = $sim->estimated_deaths ?? 0;
        if ($deaths > 1000) return 55;
        if ($deaths > 100)  return 65;
        if ($deaths > 10)   return 75;
        return 85;
    }

    /** Konversi skor total ke grade A-E. */
    public function grade(float $total): string
    {
        return $total >= 90 ? 'A' : ($total >= 80 ? 'B' : ($total >= 70 ? 'C' : ($total >= 60 ? 'D' : 'E')));
    }
}
