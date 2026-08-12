<?php

namespace App\Http\Controllers;

use App\Models\ExerciseSession;
use App\Models\OrderBoard;
use App\Models\OrbatUnit;
use Illuminate\Http\Request;

class OperasiController extends Controller
{
    // Order board list
    public function index(ExerciseSession $session)
    {
        $orders = $session->orders()->orderBy('nomor', 'desc')->get();
        $orbat = $session->orbatUnits()->get();
        return view('operasi.index', compact('session', 'orders', 'orbat'));
    }

    // Buat order baru
    public function store(Request $request, ExerciseSession $session)
    {
        $request->validate([
            'nomor' => 'required',
            'isi' => 'required',
        ]);

        $session->orders()->create([
            'nomor' => $request->nomor,
            'jenis' => $request->jenis ?? 'perintah',
            'tujuan_satker' => $request->tujuan_satker ?? 'all',
            'isi' => $request->isi,
            'status' => 'dikirim',
            'dibuat_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Order terkirim.');
    }

    // Update order status
    public function updateStatus(Request $request, ExerciseSession $session, OrderBoard $order)
    {
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status diperbarui.');
    }

    // ORBAT board
    public function orbat(ExerciseSession $session)
    {
        $units = $session->orbatUnits()->orderBy('satker')->get();
        return view('operasi.orbat', compact('session', 'units'));
    }

    // Update ORBAT unit
    public function updateOrbat(Request $request, ExerciseSession $session, OrbatUnit $unit)
    {
        $request->validate([
            'kekuatan' => 'nullable|integer',
            'status' => 'nullable',
        ]);

        $unit->update($request->only(['kekuatan', 'status', 'latitude', 'longitude']));
        return back()->with('success', 'ORBAT diperbarui.');
    }
}
