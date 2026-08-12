<?php

namespace App\Http\Controllers;

use App\Models\ExerciseSession;

class VideoWallController extends Controller
{
    // Kiosk mode - COP read-only untuk videotron
    public function show(ExerciseSession $session, string $layout = 'full')
    {
        // Layout A: full map | Layout B: map + dashboard | Layout C: 2x2 grid
        $session->load(['orbatUnits', 'preset']);

        if ($session->status === 'running') {
            $session->tickTimer();
        }

        return view('videowall.show', compact('session', 'layout'));
    }

    // API polling untuk auto-refresh COP (tanpa auth khusus kiosk)
    public function data(ExerciseSession $session)
    {
        $session->load('orbatUnits');

        return response()->json([
            'session' => [
                'nama' => $session->nama,
                'status' => $session->status,
                't_plus_detik' => $session->t_plus_detik,
            ],
            'units' => $session->orbatUnits,
            'latest_injects' => $session->injects()
                ->where('status', 'delivered')
                ->orderByDesc('delivered_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
