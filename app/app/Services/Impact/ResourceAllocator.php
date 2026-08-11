<?php
namespace App\Services\Impact;

class ResourceAllocator
{
    /** Alokasi sumber daya berdasarkan korban/kerusakan. */
    public static function allocate(array $result): array
    {
        $deaths = $result['deaths'] ?? 0;
        $injured = $result['injured'] ?? 0;
        $displaced = $result['displaced'] ?? 0;
        $destroyed = $result['destroyed'] ?? 0;
        $damaged = $result['damaged'] ?? 0;
        $affected = $result['affected'] ?? 0;

        // Tim SAR: 1 tim per 200 korban (min 1)
        $sar = max(1, (int) ceil(($deaths + $injured) / 200));
        // Personel gabungan: TNI+Polri+BPBD — 1 per 50 korban terpapar
        $personel = max(50, (int) ceil($affected / 250));
        // Ambulans: 1 per 250 luka
        $ambulance = max(1, (int) ceil($injured / 250));
        // Tenda pengungsi: 1 per 50 pengungsi
        $tenda = max(5, (int) ceil($displaced / 50));
        // Logistik (ton): 1 ton per 100 pengungsi
        $logistik = max(10, (int) ceil($displaced / 100));
        // Alat berat: 1 per 500 bangunan rusak
        $alatBerat = max(1, (int) ceil(($damaged + $destroyed) / 500));
        // Posko kesehatan: 1 per 1000 korban
        $posko = max(1, (int) ceil(($injured + $deaths) / 1000));
        // Kendaraan taktis (militer): 1 per 300 orang (hanya konflik)
        $rantis = max(1, (int) ceil($affected / 1500));

        // Total biaya respon (USD)
        $biaya = $personel * 1000 + $ambulance * 50000 + $tenda * 2000
               + $logistik * 1500 + $alatBerat * 80000 + $posko * 150000
               + $sar * 120000 + $rantis * 250000;

        return [
            'tim_sar' => $sar,
            'personel_gabungan' => $personel,
            'ambulans' => $ambulance,
            'tenda_pengungsi' => $tenda,
            'logistik_ton' => $logistik,
            'alat_berat' => $alatBerat,
            'posko_kesehatan' => $posko,
            'kendaraan_taktis' => $rantis,
            'estimasi_biaya_respon_usd' => (int) round($biaya),
        ];
    }
}