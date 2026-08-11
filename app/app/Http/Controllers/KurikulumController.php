<?php
namespace App\Http\Controllers;

use App\Models\KurikulumLevel;
use App\Models\KurikulumMapping;
use App\Models\KurikulumProgress;
use App\Models\User;
use App\Models\LeadershipAssessment;
use App\Services\AnalitikAIService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * KurikulumController — Integrasi Kurikulum Sespimmen & Sespimti
 * Pemetaan level pendidikan → skenario → progress peserta.
 */
class KurikulumController extends Controller
{
    public function index(Request $request): View
    {
        $levels = KurikulumLevel::with('mappings')->get();
        $progress = KurikulumProgress::with('user', 'level', 'mapping.assessment')
            ->when($request->filled('level'), fn ($q) => $q->where('kurikulum_level_id', $request->level))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate(20)->withQueryString();

        $users = User::latest()->get();
        $analitik = app(AnalitikAIService::class);

        return view('kurikulum.index', compact('levels', 'progress', 'users'));
    }

    /** Tambah mapping skenario → level kurikulum */
    public function storeMapping(Request $request)
    {
        $validated = $request->validate([
            'kurikulum_level_id' => 'required|exists:kurikulum_levels,id',
            'tipe_skenario' => 'required|string',
            'kode_skenario' => 'required|string',
            'nama_skenario' => 'required|string|max:255',
            'jam_pelatihan' => 'required|integer|min:1',
            'objektif' => 'nullable|string',
        ]);

        KurikulumMapping::create($validated);
        return back()->with('success', 'Mapping skenario ditambahkan.');
    }

    /** Mulai / update progress peserta */
    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kurikulum_level_id' => 'required|exists:kurikulum_levels,id',
            'kurikulum_mapping_id' => 'nullable|exists:kurikulum_mappings,id',
            'leadership_assessment_id' => 'nullable|exists:leadership_assessments,id',
            'status' => 'required|in:belum,berlangsung,selesai',
            'skor' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        KurikulumProgress::create($validated + [
            'mulai' => $validated['status'] === 'berlangsung' ? now() : null,
        ]);

        return back()->with('success', 'Progress peserta dicatat.');
    }

    public function updateProgress(Request $request, KurikulumProgress $progress)
    {
        $validated = $request->validate([
            'status' => 'required|in:belum,berlangsung,selesai',
            'skor' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $progress->update($validated + [
            'selesai' => $validated['status'] === 'selesai' ? now() : null,
        ]);

        return back()->with('success', 'Progress diupdate.');
    }
}
