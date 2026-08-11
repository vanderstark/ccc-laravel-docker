<?php
namespace App\Http\Controllers;

use App\Models\AarSession;
use App\Models\LeadershipAssessment;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * AARController — After Action Review Workflow
 * Pola: briefing → simulation → decision → AAR → feedback
 * (Sesuai PDF poin 3e: briefing–simulation–decision–after action review–feedback)
 */
class AarController extends Controller
{
    /** Timeline AAR untuk satu sesi. */
    public function index(Request $request): View
    {
        $sessions = AarSession::with('user', 'simulation')
            ->when($request->filled('tahap'), fn($q) => $q->where('tahap', $request->tahap))
            ->when($request->filled('simulation_id'), fn($q) => $q->where('simulation_id', $request->simulation_id))
            ->latest()->paginate(20)->withQueryString();

        $simulations = Simulation::latest()->limit(50)->get();
        $tahaps = ['briefing', 'simulation', 'decision', 'aar', 'feedback'];

        return view('aar.index', compact('sessions', 'simulations', 'tahaps'));
    }

    /** Store tahap baru. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'simulation_id' => 'nullable|exists:simulations,id',
            'tahap'         => 'required|in:briefing,simulation,decision,aar,feedback',
            'judul'         => 'required|string|max:255',
            'konten'        => 'nullable|string',
        ]);

        AarSession::create([
            'user_id'  => auth()->id(),
            'simulation_id' => $validated['simulation_id'] ?? null,
            'tahap'    => $validated['tahap'],
            'judul'    => $validated['judul'],
            'konten'   => $validated['konten'] ?? null,
            'data'     => ['ip' => $request->ip()],
        ]);

        return back()->with('success', 'Tahap AAR "' . $validated['judul'] . '" berhasil ditambahkan.');
    }

    /** Generate laporan AAR lengkap (markdown). */
    public function report(Request $request, ?Simulation $simulation = null)
    {
        $query = AarSession::with('user', 'simulation')->orderBy('created_at');
        if ($simulation) $query->where('simulation_id', $simulation->id);
        $sessions = $query->get();

        $md = "# LAPORAN AFTER ACTION REVIEW\n\n";
        $md .= "Dibuat: " . now()->format('d M Y H:i') . "\n\n";

        if ($simulation) {
            $md .= "## Simulasi: #{$simulation->id} — {$simulation->disasterType?->nama}\n";
            $md .= "- Lokasi: {$simulation->location}\n";
            $md .= "- Alert: {$simulation->alert_level}\n";
            $md .= "- Durasi: {$simulation->duration_minutes} menit\n\n";
        }

        $md .= "## Kronologi (Briefing → Simulasi → Keputusan → AAR → Feedback)\n\n";

        $kelompok = $sessions->groupBy('tahap');
        $urutan = ['briefing', 'simulation', 'decision', 'aar', 'feedback'];
        $label = ['briefing' => '📋 BRIEFING', 'simulation' => '🎮 SIMULASI', 'decision' => '🧭 KEPUTUSAN', 'aar' => '🔍 AFTER ACTION REVIEW', 'feedback' => '💬 FEEDBACK'];

        foreach ($urutan as $tahap) {
            $items = $kelompok->get($tahap, collect());
            $md .= "### " . ($label[$tahap] ?? strtoupper($tahap)) . "\n\n";
            if ($items->isEmpty()) {
                $md .= "_Belum ada catatan_\n\n";
            } else {
                foreach ($items as $s) {
                    $md .= "- **[" . $s->created_at?->format('H:i') . "]** {$s->judul}";
                    $md .= " _(oleh: " . ($s->user?->name ?? 'System') . ")_\n";
                    if ($s->konten) $md .= "  > " . str_replace("\n", "\n  > ", $s->konten) . "\n";
                }
                $md .= "\n";
            }
        }

        // Assessment terkait
        $assessments = LeadershipAssessment::whereIn('simulation_id', $sessions->pluck('simulation_id')->filter())->get();
        if ($assessments->isNotEmpty()) {
            $md .= "## Hasil Penilaian Kepemimpinan\n\n";
            $md .= "| Peserta | Total | Grade |\n|---|---|---|\n";
            foreach ($assessments as $a) {
                $md .= "| {$a->user?->name} | {$a->skor_total} | **{$a->grade}** |\n";
            }
            $md .= "\n";
        }

        return response($md, 200, ['Content-Type' => 'text/markdown; charset=utf-8'])
            ->header('Content-Disposition', 'attachment; filename="AAR-Report-' . now()->format('Ymd-His') . '.md"');
    }

    /** Hapus tahap. */
    public function destroy(AarSession $session)
    {
        $session->delete();
        return back()->with('success', 'Catatan AAR dihapus.');
    }
}
