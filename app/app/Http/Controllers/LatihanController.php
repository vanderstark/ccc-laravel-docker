<?php

namespace App\Http\Controllers;

use App\Models\ExerciseSession;
use App\Models\Inject;
use App\Models\FogOfWar;
use App\Models\InjectLog;
use Illuminate\Http\Request;

class LatihanController extends Controller
{
    // Dashboard sesi latihan
    public function index(Request $request)
    {
        $sessions = ExerciseSession::with(['simulation', 'preset', 'creator'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('latihan.index', compact('sessions'));
    }

    // Buat sesi baru
    public function create()
    {
        $simulations = \App\Models\Simulation::orderByDesc('created_at')->get();
        $presets = \App\Models\Preset::orderBy('code')->get();

        return view('latihan.create', compact('simulations', 'presets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:exercise_sessions,kode',
        ]);

        $session = ExerciseSession::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'simulation_id' => $request->simulation_id,
            'preset_id' => $request->preset_id,
            'objectives' => $request->objectives,
            'roe' => $request->roe,
            'durasi_menit' => $request->durasi_menit ?? 120,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Generate 7 satker default
        foreach (OrbatUnit::SATKER as $code => $nama) {
            \App\Models\OrbatUnit::create([
                'session_id' => $session->id,
                'satker' => $code,
                'nama_unit' => $nama,
                'jenis' => 'personel',
                'kekuatan' => 0,
                'status' => 'siaga',
            ]);
        }

        // Generate fog of war default per satker
        foreach (array_keys(OrbatUnit::SATKER) as $satker) {
            FogOfWar::create([
                'session_id' => $session->id,
                'satker' => $satker,
                'layer' => 'situasi',
                'enabled' => true,
            ]);
        }

        return redirect()->route('latihan.show', $session->id)
            ->with('success', 'Sesi dibuat. Siap untuk briefing.');
    }

    public function show(ExerciseSession $session)
    {
        $session->load(['simulation', 'preset', 'creator', 'injects', 'orbatUnits', 'orders']);

        // Timer tick
        if ($session->status === 'running') {
            $session->tickTimer();
        }

        return view('latihan.show', compact('session'));
    }

    // State transition
    public function transition(Request $request, ExerciseSession $session)
    {
        $to = $request->input('status');
        if (!$session->canTransition($to)) {
            return back()->with('error', "Transisi {$session->status} → {$to} tidak valid.");
        }

        $update = ['status' => $to];
        if ($to === 'running') {
            $update['mulai_pada'] = $session->mulai_pada ?? now();
        } elseif ($to === 'ended') {
            $update['akhir_pada'] = now();
        }

        $session->update($update);

        return back()->with('success', "Sesi → {$to}");
    }

    // T+ timer API
    public function timer(ExerciseSession $session)
    {
        $session->tickTimer();
        return response()->json([
            't_plus_detik' => $session->t_plus_detik,
            'status' => $session->status,
            'mulai_pada' => $session->mulai_pada,
        ]);
    }

    // ===== INJECTS (Menu EXCON) =====

    public function injects(ExerciseSession $session)
    {
        $injects = $session->injects()->orderBy('t_plus_sec')->get();
        return view('latihan.injects', compact('session', 'injects'));
    }

    public function storeInject(Request $request, ExerciseSession $session)
    {
        $request->validate([
            'kode' => 'required',
            'title' => 'required',
            'message' => 'required',
        ]);

        $session->injects()->create([
            'kode' => $request->kode,
            'title' => $request->title,
            'message' => $request->message,
            'visible_to' => $request->visible_to ?? 'all',
            't_plus_sec' => $request->t_plus_sec ?? 0,
            'map_effect' => $request->map_effect,
            'requires_action' => $request->requires_action,
            'fail_effect' => $request->fail_effect,
        ]);

        return back()->with('success', 'Inject ditambahkan.');
    }

    public function deliverInject(Inject $inject)
    {
        $inject->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return back()->with('success', "Inject {$inject->kode} dikirim.");
    }

    // ===== FOG OF WAR (EXCON control) =====

    public function fog(ExerciseSession $session)
    {
        $fogList = $session->fogOfWar()->get();
        return view('latihan.fog', compact('session', 'fogList'));
    }

    public function toggleFog(Request $request, ExerciseSession $session)
    {
        $request->validate([
            'satker' => 'required',
        ]);

        $fog = FogOfWar::where('session_id', $session->id)
            ->where('satker', $request->satker)
            ->firstOrCreate([
                'session_id' => $session->id,
                'satker' => $request->satker,
                'layer' => 'situasi',
            ]);

        $fog->update(['enabled' => !$fog->enabled]);

        return back()->with('success', "Fog {$request->satker}: " . ($fog->enabled ? 'ON' : 'OFF'));
    }

    // ===== DECISION LOG =====

    public function decisions(ExerciseSession $session)
    {
        $decisions = $session->decisionLogs()->with('user')->orderByDesc('t_plus_sec')->get();
        return view('latihan.decisions', compact('session', 'decisions'));
    }

    public function storeDecision(Request $request, ExerciseSession $session)
    {
        $request->validate([
            'keputusan' => 'required',
            'pic' => 'required',
        ]);

        $session->decisionLogs()->create([
            'user_id' => auth()->id(),
            'satker' => $request->satker,
            'keputusan' => $request->keputusan,
            'pic' => $request->pic,
            't_plus_sec' => $session->t_plus_detik,
        ]);

        return back()->with('success', 'Keputusan dicatat.');
    }
}
