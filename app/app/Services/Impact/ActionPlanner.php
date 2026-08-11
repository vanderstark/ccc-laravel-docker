<?php
namespace App\Services\Impact;

class ActionPlanner
{
    /** Daftar rekomendasi 4 fase (mitigasi, kesiapsiagaan, tanggap darurat, pemulihan). */
    public static function plan(string $disasterCode, string $alertLevel, array $result): array
    {
        $kategori = self::kategori($disasterCode);
        return [
            'mitigasi' => self::mitigasi($kategori, $disasterCode),
            'kesiapsiagaan' => self::kesiapsiagaan($kategori, $alertLevel),
            'tanggap_darurat' => self::tanggap($kategori, $alertLevel, $result),
            'pemulihan' => self::pemulihan($kategori, $result),
        ];
    }

    private static function kategori(string $code): string
    {
        $militer = ['conflict', 'maritime', 'air', 'combined'];
        $sosial = ['social_conflict', 'riot', 'terrorism', 'mass_violence', 'demonstration'];
        $geo = ['earthquake', 'tsunami', 'volcano', 'landslide', 'liquefaction'];
        if (in_array($code, $militer)) return 'militer';
        if (in_array($code, $sosial)) return 'sosial';
        if (in_array($code, $geo)) return 'geologis';
        return 'umum';
    }

    private static function mitigasi(string $kategori, string $code): array
    {
        $items = [
            'umum' => 'Sosialisasi kebencanaan & pembentukan desa tangguh bencana',
            'geologis' => 'Pemetaan zona rawan gempa/longsor & penguatan bangunan tahan gempa',
            'sosial' => 'Dialog lintas sektor & deteksi dini potensi konflik',
            'militer' => 'Peningkatan kesiapan satuan & patroli perbatasan',
        ];
        $specific = match ($code) {
            'tsunami' => 'Pembangunan shelter evakuasi vertikal & pemasangan sirine tsunami',
            'flood', 'flash_flood' => 'Normalisasi sungai & pembersihan drainase berkala',
            'volcano' => 'Pembuatan peta kawasan rawan bencana (KRB) gunung api',
            'forest_fire' => 'Pengendalian karhutla: sekat kanal, pos pantau, patroli terpadu',
            'earthquake' => 'Audit ketahanan gedung vital (kantor polisi, RS, sekolah)',
            default => '',
        };
        if ($specific) $items['geologis'] = $specific;
        return $items;
    }

    private static function kesiapsiagaan(string $kategori, string $alert): array
    {
        $skala = match ($alert) {
            'merah' => 'Aktivasi penuh Posko & mobilisasi menyeluruh',
            'oranye' => 'Siaga darurat & gladi evakuasi skala besar',
            'kuning' => 'Peningkatan patroli & briefing rutin',
            default => 'Pemantauan rutin & pemeliharaan alat',
        };
        return [
            'Latihan & gladi simulasi berkala (minimal 2x/tahun)',
            'Pengecekan kesiapan alat & logistik',
            'Pembaruan data kontak & jalur evakuasi',
            $skala,
        ];
    }

    private static function tanggap(string $kategori, string $alert, array $result): array
    {
        $deaths = $result['deaths'] ?? 0;
        $injured = $result['injured'] ?? 0;
        return [
            'Aktivasi Posko Komando & notifikasi seluruh unsur terkait',
            "Evakuasi korban: {$injured} luka + {$deaths} meninggal diprioritaskan",
            'Pendirian dapur umum & tenda pengungsian',
            match ($kategori) {
                'militer' => 'Pengamanan wilayah & penetapan area operasi militer (AOM)',
                'sosial' => 'Pengendalian massa dengan tindakan humanis (Polri)',
                'geologis' => 'Pencarian & pertolongan korban tertimbun (SAR gabungan)',
                default => 'Penanganan darurat sesuai SOP BPBD',
            },
            'Pengamanan lokasi & pengaturan lalu lintas (Polantas)',
            'Monitoring perkembangan & pelaporan berkala ke pimpinan',
        ];
    }

    private static function pemulihan(string $kategori, array $result): array
    {
        $displaced = $result['displaced'] ?? 0;
        return [
            "Relokasi & penataan kembali {$displaced} pengungsi",
            'Rehabilitasi infrastruktur vital (jalan, listrik, air bersih)',
            'Pendampingan psikososial bagi korban',
            'Rekonstruksi bangunan rusak dengan standar tahan bencana',
            match ($kategori) {
                'militer' => 'Normalisasi keamanan & pengembalian fungsi pemerintahan',
                'sosial' => 'Rekonsiliasi & pemulihan kepercayaan antar kelompok',
                default => 'Evaluasi & penyusunan laporan akhir bencana',
            },
        ];
    }
}