<?php
namespace App\Http\Controllers;

use App\Models\LeadershipAssessment;
use App\Models\User;
use App\Models\Simulation;
use App\Models\AarSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadershipController extends Controller
{
    /** Dashboard Pimpinan — KPI, heatmap, trend. */
    public function dashboard(Request $request): View
    {
        $query = LeadershipAssessment::query();

        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('grade'))   $query->where('grade', $request->grade);

        $assessments = $query->with('user')->latest()->paginate(15)->withQueryString();

        // KPI
        $all = LeadershipAssessment::all();
        $kpi = [
            'total'        => $all->count(),
            'rata_total'   => round($all->avg('skor_total') ?? 0, 1),
            'rata_keputusan'  => round($all->avg('skor_keputusan') ?? 0, 1),
            'rata_kecepatan'  => round($all->avg('skor_kecepatan') ?? 0, 1),
            'rata_kolaborasi' => round($all->avg('skor_kolaborasi') ?? 0, 1),
            'rata_komunikasi' => round($all->avg('skor_komunikasi') ?? 0, 1),
            'rata_integritas' => round($all->avg('skor_integritas') ?? 0, 1),
            'rata_risiko'     => round($all->avg('skor_risiko') ?? 0, 1),
            'grade_a'      => $all->where('grade', 'A')->count(),
            'grade_b'      => $all->where('grade', 'B')->count(),
            'grade_c'      => $all->where('grade', 'C')->count(),
            'grade_d'      => $all->where('grade', 'D')->count(),
            'grade_e'      => $all->where('grade', 'E')->count(),
        ];

        // Per-user ranking
        $rankings = LeadershipAssessment::selectRaw('user_id, COUNT(*) as total_penilaian, ROUND(AVG(skor_total),1) as rata_skor')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('rata_skor')
            ->limit(10)
            ->get();

        // Trend per hari (14 hari terakhir)
        $trend = LeadershipAssessment::selectRaw('DATE(created_at) as tanggal, ROUND(AVG(skor_total),1) as rata')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(fn($t) => ['tanggal' => $t->tanggal, 'rata' => (float)$t->rata])
            ->toArray();

        // AAR terakhir
        $aar = AarSession::with('user')->latest()->limit(5)->get();

        $users = User::all();

        return view('leadership.dashboard', compact('assessments', 'kpi', 'rankings', 'trend', 'aar', 'users'));
    }

    /** Form penilaian manual. */
    public function create(Request $request): View
    {
        $simulations = Simulation::latest()->limit(50)->get();
        $users = User::all();
        return view('leadership.create', compact('simulations', 'users'));
    }

    /** Simpan penilaian manual. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'simulation_id'    => 'nullable|exists:simulations,id',
            'skor_keputusan'   => 'required|numeric|min:0|max:100',
            'skor_kecepatan'   => 'required|numeric|min:0|max:100',
            'skor_kolaborasi'  => 'required|numeric|min:0|max:100',
            'skor_komunikasi'  => 'required|numeric|min:0|max:100',
            'skor_integritas'  => 'required|numeric|min:0|max:100',
            'skor_risiko'      => 'required|numeric|min:0|max:100',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $sim = $validated['simulation_id'] ?? null ? Simulation::find($validated['simulation_id']) : null;

        app(\App\Services\LeadershipAssessmentService::class)->nilaiManual($user, [
            'simulation_id' => $sim?->id,
            'scenario_type' => 'manual',
            'scenario_name' => $sim?->disasterType?->nama ?? 'Manual',
            'skor_keputusan'  => $validated['skor_keputusan'],
            'skor_kecepatan'  => $validated['skor_kecepatan'],
            'skor_kolaborasi' => $validated['skor_kolaborasi'],
            'skor_komunikasi' => $validated['skor_komunikasi'],
            'skor_integritas' => $validated['skor_integritas'],
            'skor_risiko'     => $validated['skor_risiko'],
            'catatan'         => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('leadership.dashboard')->with('success', 'Penilaian kepemimpinan berhasil disimpan.');
    }

    /** Detail assessment + link AAR. */
    public function show(LeadershipAssessment $assessment): View
    {
        $aar = AarSession::where('leadership_assessment_id', $assessment->id)->orWhere('simulation_id', $assessment->simulation_id)->get();
        return view('leadership.show', compact('assessment', 'aar'));
    }

    /** Hapus assessment. */
    public function destroy(LeadershipAssessment $assessment)
    {
        $assessment->delete();
        return back()->with('success', 'Penilaian dihapus.');
    }
}
