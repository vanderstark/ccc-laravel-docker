<?php
namespace App\Http\Controllers;

use App\Models\Simulation;
use App\Models\MediaSosial;
use App\Models\KomunikasiKrisis;
use App\Services\AnalitikAIService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * KomunikasiKrisisController — Modul Komunikasi Krisis + Media Sosial
 * + Dashboard Analitik/AI (PDF poin 3b).
 */
class KomunikasiKrisisController extends Controller
{
    public function __construct(private AnalitikAIService $analitik) {}

    /** Dashboard gabungan: monitoring medsos + komunikasi krisis + analitik. */
    public function index(Request $request): View
    {
        $simulations = Simulation::latest()->limit(50)->get();

        $medsos = MediaSosial::with('simulation')
            ->when($request->filled('simulation_id'), fn ($q) => $q->where('simulation_id', $request->simulation_id))
            ->when($request->filled('sentimen'), fn ($q) => $q->where('sentimen', $request->sentimen))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate(15)->withQueryString();

        $komunikasi = KomunikasiKrisis::with('simulation')->latest()->limit(10)->get();
        $analitik = $this->analitik->dashboardAnalitik();

        // Ringkasan & rekomendasi untuk simulasi terpilih
        $ringkasan = null;
        $rekomendasi = [];
        if ($request->filled('simulation_id') && $sim = Simulation::find($request->simulation_id)) {
            $ringkasan = $this->analitik->ringkasanSituasi($sim);
            $rekomendasi = $this->analitik->rekomendasi($sim);
        }

        return view('krisis.index', compact('simulations', 'medsos', 'komunikasi', 'analitik', 'ringkasan', 'rekomendasi'));
    }

    /** Simpan konten media sosial (dengan analisis otomatis). */
    public function storeMedsos(Request $request)
    {
        $validated = $request->validate([
            'simulation_id' => 'nullable|exists:simulations,id',
            'platform' => 'required|string|max:50',
            'jenis_konten' => 'required|in:berita,rumor,hoax,seruan,info_resmi',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'sumber' => 'nullable|string|max:255',
            'jangkauan' => 'nullable|integer|min:0',
        ]);

        $analisis = MediaSosial::analyzeRumor($validated['konten']);
        $status = $validated['jenis_konten'] === 'hoax' ? 'hoax_terkonfirmasi' : 'aktif';
        if ($analisis['is_hoax']) $status = 'hoax_terkonfirmasi';

        MediaSosial::create([
            'simulation_id' => $validated['simulation_id'] ?? null,
            'platform' => $validated['platform'],
            'jenis_konten' => $validated['jenis_konten'],
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'sumber' => $validated['sumber'] ?? null,
            'sentimen' => $analisis['sentiment'],
            'jangkauan' => $validated['jangkauan'] ?? 0,
            'status' => $status,
            'analisis' => $analisis,
        ]);

        return back()->with('success', 'Konten media sosial ditambahkan & dianalisis otomatis.');
    }

    /** Simpan komunikasi krisis (siaran pers / briefing media). */
    public function storeKrisis(Request $request)
    {
        $validated = $request->validate([
            'simulation_id' => 'nullable|exists:simulations,id',
            'jenis' => 'required|in:siaran_pers,briefing_media,pernyataan_pimpinan,klarifikasi',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'audiens' => 'nullable|string|max:255',
            'status' => 'required|in:draf,terbit',
        ]);

        KomunikasiKrisis::create($validated + ['data' => ['ip' => $request->ip()]]);

        return back()->with('success', 'Komunikasi krisis disimpan.');
    }

    /** Update status konten medsos (ditangani / hoax terkonfirmasi). */
    public function updateStatus(Request $request, MediaSosial $media)
    {
        $request->validate(['status' => 'required|in:aktif,ditangani,hoax_terkonfirmasi']);
        $media->update(['status' => $request->status]);
        return back()->with('success', 'Status konten diubah.');
    }

    /** Terbitkan / ubah komunikasi krisis. */
    public function updateKrisis(Request $request, KomunikasiKrisis $krisis)
    {
        $validated = $request->validate(['status' => 'required|in:draf,terbit,edit']);
        $krisis->update($validated);
        return back()->with('success', 'Status komunikasi krisis diubah.');
    }

    public function destroyMedsos(MediaSosial $media)
    {
        $media->delete();
        return back()->with('success', 'Konten medsos dihapus.');
    }

    public function destroyKrisis(KomunikasiKrisis $krisis)
    {
        $krisis->delete();
        return back()->with('success', 'Komunikasi krisis dihapus.');
    }
}
