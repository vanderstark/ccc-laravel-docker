<?php
namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index(Request $request)
    {
        $markers = Marker::with(['simulation'])
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(20);

        return view('tactical.markers', compact('markers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:unit,incident,asset',
            'nama' => 'required|string|max:120',
            'kategori' => 'nullable|string|max:80',
            'lat' => 'required|numeric|between:-11,6',
            'lon' => 'required|numeric|between:95,141',
            'status' => 'nullable|in:active,standby,on_mission',
            'simulation_id' => 'nullable|exists:simulations,id',
            'extra' => 'nullable|array',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'active';
        $marker = Marker::create($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'entity' => 'marker',
            'entity_id' => $marker->id,
            'data' => ['nama' => $marker->nama, 'type' => $marker->type],
            'ip' => request()->ip(),
        ]);

        return redirect()->route('tactical.markers')->with('success', 'Marker "' . $marker->nama . '" ditambahkan.');
    }

    public function update(Request $request, Marker $marker)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:120',
            'kategori' => 'nullable|string|max:80',
            'lat' => 'required|numeric|between:-11,6',
            'lon' => 'required|numeric|between:95,141',
            'status' => 'required|in:active,standby,on_mission',
        ]);

        $marker->update($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'entity' => 'marker',
            'entity_id' => $marker->id,
            'data' => ['nama' => $marker->nama],
            'ip' => request()->ip(),
        ]);

        return back()->with('success', 'Marker diperbarui.');
    }

    public function destroy(Marker $marker)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'entity' => 'marker',
            'entity_id' => $marker->id,
            'data' => ['nama' => $marker->nama],
            'ip' => request()->ip(),
        ]);

        $marker->delete();
        return back()->with('success', 'Marker dihapus.');
    }
}
