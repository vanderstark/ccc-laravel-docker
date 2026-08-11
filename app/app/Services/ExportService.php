<?php
namespace App\Services;

use App\Models\Simulation;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /** Export CSV — laporan simulasi lengkap. */
    public function simulationCsv(iterable $simulations): StreamedResponse
    {
        return response()->streamDownload(function () use ($simulations) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 agar Excel buka dengan benar

            fputcsv($out, [
                'ID', 'Tipe', 'Kode', 'Lokasi', 'Lat', 'Lon',
                'Tingkat Alert', 'Klasifikasi', 'Terdampak', 'Meninggal',
                'Luka', 'Mengungsi', 'Rumah Rusak', 'Rumah Hancur',
                'Kerugian (USD)', 'Status', 'Dibuat', 'Waktu',
            ]);

            foreach ($simulations as $s) {
                fputcsv($out, [
                    $s->id,
                    $s->disasterType?->nama ?? $s->disaster_type,
                    $s->disaster_type,
                    $s->location,
                    $s->lat,
                    $s->lon,
                    $s->alert_level,
                    $s->classification,
                    $s->affected_population ?? 0,
                    $s->estimated_deaths ?? 0,
                    $s->estimated_injured ?? 0,
                    $s->displaced ?? 0,
                    $s->houses_damaged ?? 0,
                    $s->houses_destroyed ?? 0,
                    $s->economic_loss_usd ?? 0,
                    $s->status,
                    $s->created_at?->format('Y-m-d H:i'),
                    $s->duration_minutes,
                ]);
            }
            fclose($out);
        }, 'laporan-simulasi-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** Export briefing ringkas (markdown) — siap di-paste ke laporan. */
    public function briefingMarkdown(Simulation $sim): string
    {
        $d = $sim->disasterType;
        $res = $sim->resource_allocation ?? [];
        $acts = $sim->action_plan ?? [];

        $md = "# BRIEFING SIMULASI — " . ($d->nama ?? strtoupper($sim->disaster_type)) . "\n\n";
        $md .= "- **ID Simulasi:** #{$sim->id}\n";
        $md .= "- **Lokasi:** {$sim->location} ({$sim->lat}, {$sim->lon})\n";
        $md .= "- **Tingkat Alert:** " . strtoupper($sim->alert_level) . "\n";
        $md .= "- **Klasifikasi:** {$sim->classification}\n";
        $md .= "- **Waktu:** " . $sim->created_at?->format('d M Y H:i') . "\n\n";

        $md .= "## Dampak\n\n";
        $md .= "| Metrik | Nilai |\n|---|---|\n";
        $md .= "| Populasi Terdampak | " . number_format($sim->affected_population ?? 0) . " |\n";
        $md .= "| Meninggal | " . number_format($sim->estimated_deaths ?? 0) . " |\n";
        $md .= "| Luka-luka | " . number_format($sim->estimated_injured ?? 0) . " |\n";
        $md .= "| Mengungsi | " . number_format($sim->displaced ?? 0) . " |\n";
        $md .= "| Rumah Rusak | " . number_format($sim->houses_damaged ?? 0) . " |\n";
        $md .= "| Rumah Hancur | " . number_format($sim->houses_destroyed ?? 0) . " |\n";
        $md .= "| Kerugian | \$" . number_format($sim->economic_loss_usd ?? 0) . " |\n\n";

        if (!empty($res)) {
            $md .= "## Alokasi Sumber Daya\n\n";
            foreach ($res as $k => $v) {
                if (is_scalar($v)) {
                    $md .= "- **" . ucwords(str_replace('_', ' ', $k)) . ":** {$v}\n";
                }
            }
            $md .= "\n";
        }

        if (!empty($acts)) {
            $md .= "## Rencana Aksi (4 Fase)\n\n";
            foreach ($acts as $fase => $items) {
                $md .= "### " . ucwords(str_replace('_', ' ', $fase)) . "\n";
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $md .= "- {$item}\n";
                    }
                } else {
                    $md .= "- {$items}\n";
                }
                $md .= "\n";
            }
        }

        $md .= "---\n*Dokumen dihasilkan otomatis oleh Crisis Command Center — data simulasi/latihan.*\n";
        return $md;
    }
}
