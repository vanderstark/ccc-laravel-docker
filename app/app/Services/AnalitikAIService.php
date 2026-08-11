<?php
namespace App\Services;

use App\Models\Simulation;
use App\Models\LeadershipAssessment;
use App\Models\MediaSosial;
use App\Models\KomunikasiKrisis;
use App\Models\AarSession;
use App\Models\Marker;

/**
 * AnalitikAIService — Dukungan Analitik/AI (rule-based, offline)
 * Menghasilkan: ringkasan situasi, rekomendasi keputusan, deteksi anomali,
 * dan analisis sentimen media sosial — semua tanpa API eksternal.
 */
class AnalitikAIService
{
    /** Ringkasan situasi otomatis dari satu simulasi. */
    public function ringkasanSituasi(Simulation $sim): array
    {
        $markers = Marker::where('simulation_id', $sim->id)->get();
        $unitAktif = $markers->where('type', 'unit')->where('status', 'active')->count();
        $incident = $markers->where('type', 'incident')->count();
        $asset = $markers->where('type', 'asset')->count();
        $medsos = MediaSosial::where('simulation_id', $sim->id)->get();
        $sentimenNegatif = $medsos->where('sentimen', 'negatif')->count();
        $hoax = $medsos->where('status', 'hoax_terkonfirmasi')->count();

        // Skor urgensi (0-100)
        $skor = 20;
        $skor += min(30, $incident * 5);
        $skor += min(20, $sentimenNegatif * 4);
        $skor += min(15, $hoax * 5);
        $skor += min(15, $sim->alert_level === 'merah' ? 15 : ($sim->alert_level === 'kuning' ? 10 : 5));

        $tingkat = $skor >= 70 ? 'KRITIS' : ($skor >= 40 ? 'TINGGI' : ($skor >= 20 ? 'SEDANG' : 'RENDAH'));

        return [
            'simulation_id' => $sim->id,
            'lokasi' => $sim->location,
            'tipe' => $sim->disasterType?->nama ?? $sim->disaster_type,
            'tingkat' => $tingkat,
            'skor' => $skor,
            'ringkasan' => sprintf(
                'Situasi %s di %s (%s). %d unit aktif, %d insiden, %d aset. Sentimen negatif: %d konten, hoax terkonfirmasi: %d.',
                $tingkat, $sim->location, $sim->disaster_type, $unitAktif, $incident, $asset, $sentimenNegatif, $hoax
            ),
            'komponen' => compact('unitAktif', 'incident', 'asset', 'sentimenNegatif', 'hoax'),
        ];
    }

    /** Rekomendasi keputusan otomatis (rule-based playbook). */
    public function rekomendasi(Simulation $sim): array
    {
        $s = $this->ringkasanSituasi($sim);
        $rekom = [];

        if ($s['skor'] >= 70) {
            $rekom[] = ['prioritas' => 'SANGAT TINGGI', 'tindakan' => 'Aktifkan pusat krisis penuh & eskalasi ke pimpinan', 'alasan' => 'Skor urgensi kritis (' . $s['skor'] . ').'];
            $rekom[] = ['prioritas' => 'SANGAT TINGGI', 'tindakan' => 'Terbitkan siaran pers & briefing media dalam 1 jam', 'alasan' => 'Sentimen negatif/hoax tinggi — komunikasi proaktif wajib.'];
        }
        if ($s['komponen']['incident'] > 0) {
            $rekom[] = ['prioritas' => 'TINGGI', 'tindakan' => 'Kerahkan unit terdekat ke titik insiden & update status tiap 30 menit', 'alasan' => 'Terdeteksi ' . $s['komponen']['incident'] . ' insiden aktif.'];
        }
        if ($s['komponen']['sentimenNegatif'] >= 3) {
            $rekom[] = ['prioritas' => 'TINGGI', 'tindakan' => 'Aktifkan tim komunikasi krisis & monitoring media sosial 24 jam', 'alasan' => 'Sentimen negatif masif (' . $s['komponen']['sentimenNegatif'] . ' konten).'];
        }
        if ($s['komponen']['hoax'] > 0) {
            $rekom[] = ['prioritas' => 'SEDANG', 'tindakan' => 'Terbitkan klarifikasi resmi & koordinasi platform untuk take-down', 'alasan' => $s['komponen']['hoax'] . ' hoax terkonfirmasi.'];
        }
        if ($s['komponen']['unitAktif'] === 0) {
            $rekom[] = ['prioritas' => 'SEDANG', 'tindakan' => 'Tandai unit cadangan & siapkan penggantian', 'alasan' => 'Tidak ada unit aktif terdeteksi.'];
        }
        if (empty($rekom)) {
            $rekom[] = ['prioritas' => 'RENDAH', 'tindakan' => 'Pertahankan monitoring rutin & siapkan skenario eskalasi', 'alasan' => 'Situasi terkendali.'];
        }

        return $rekom;
    }

    /** Skor kepemimpinan prediktif dari histori assessment. */
    public function prediksiKinerja(int $userId): array
    {
        $data = LeadershipAssessment::where('user_id', $userId)->orderBy('created_at')->get();
        if ($data->count() < 2) {
            return ['tersedia' => false, 'pesan' => 'Butuh minimal 2 penilaian untuk prediksi tren.'];
        }
        $last = $data->last()->skor_total;
        $first = $data->first()->skor_total;
        $delta = round($last - $first, 2);
        $tren = $delta > 5 ? 'Meningkat' : ($delta < -5 ? 'Menurun' : 'Stabil');
        $rata = round($data->avg('skor_total'), 2);

        return [
            'tersedia' => true,
            'total' => $data->count(),
            'rata' => $rata,
            'delta' => $delta,
            'tren' => $tren,
            'prediksi' => $tren === 'Meningkat' ? 'Progres positif — pertahankan.' : ($tren === 'Menurun' ? 'Perlu intervensi pelatihan & mentoring.' : 'Konsisten — dorong variasi skenario.'),
        ];
    }

    /** Dashboard analitik gabungan. */
    public function dashboardAnalitik(): array
    {
        $medsos = MediaSosial::all();
        $krisis = KomunikasiKrisis::all();

        return [
            'total_medsos' => $medsos->count(),
            'sentimen' => [
                'positif' => $medsos->where('sentimen', 'positif')->count(),
                'netral' => $medsos->where('sentimen', 'netral')->count(),
                'negatif' => $medsos->where('sentimen', 'negatif')->count(),
            ],
            'hoax' => $medsos->where('status', 'hoax_terkonfirmasi')->count(),
            'rumor' => $medsos->filter(fn ($m) => ($m->analisis['is_rumor'] ?? false))->count(),
            'konten_aktif' => $medsos->where('status', 'aktif')->count(),
            'komunikasi' => [
                'total' => $krisis->count(),
                'terbit' => $krisis->where('status', 'terbit')->count(),
                'draf' => $krisis->where('status', 'draf')->count(),
            ],
            'platform' => $medsos->groupBy('platform')->map->count()->sortDesc()->take(5),
        ];
    }
}
