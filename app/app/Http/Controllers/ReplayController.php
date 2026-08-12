<?php

namespace App\Http\Controllers;

use App\Models\ExerciseSession;
use App\Models\MovementLog;

class ReplayController extends Controller
{
    // Replay engine - playback timeline sesi
    public function show(ExerciseSession $session)
    {
        $session->load(['injects', 'decisionLogs.user', 'orbatUnits']);

        // Gabungkan semua event jadi timeline
        $timeline = collect();

        foreach ($session->injects as $inject) {
            $timeline->push([
                'type' => 'inject',
                't_plus_sec' => $inject->t_plus_sec,
                'title' => $inject->title,
                'detail' => $inject->message,
                'satker' => $inject->visible_to,
            ]);
        }

        foreach ($session->decisionLogs as $log) {
            $timeline->push([
                'type' => 'decision',
                't_plus_sec' => $log->t_plus_sec,
                'title' => 'Keputusan: ' . $log->pic,
                'detail' => $log->keputusan,
                'satker' => $log->satker,
            ]);
        }

        $timeline = $timeline->sortBy('t_plus_sec')->values();

        return view('replay.show', compact('session', 'timeline'));
    }

    // Heatmap data (pergerakan unit)
    public function heatmap(ExerciseSession $session)
    {
        $points = MovementLog::where('session_id', $session->id)
            ->select('latitude', 'longitude')
            ->get()
            ->map(fn ($p) => [$p->latitude, $p->longitude, 0.5]);

        return response()->json(['points' => $points]);
    }

    // Side-by-side comparison 2 sesi
    public function compare(ExerciseSession $sessionA, ExerciseSession $sessionB)
    {
        $sessionA->load(['decisionLogs', 'injects']);
        $sessionB->load(['decisionLogs', 'injects']);

        $stats = [
            'a' => [
                'nama' => $sessionA->nama,
                'total_decisions' => $sessionA->decisionLogs->count(),
                'total_injects' => $sessionA->injects->count(),
                'durasi' => $sessionA->t_plus_detik,
            ],
            'b' => [
                'nama' => $sessionB->nama,
                'total_decisions' => $sessionB->decisionLogs->count(),
                'total_injects' => $sessionB->injects->count(),
                'durasi' => $sessionB->t_plus_detik,
            ],
        ];

        return view('replay.compare', compact('sessionA', 'sessionB', 'stats'));
    }
}
