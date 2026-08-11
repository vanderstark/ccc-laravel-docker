<?php
namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::with(['simulation', 'organization'])->latest()->paginate(20);
        return view('tactical.zones', compact('zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:120',
            'jenis' => 'required|in:zona,route,objective',
            'warna' => 'nullable|string|max:7',
            'geometry' => 'required|json',
            'simulation_id' => 'nullable|exists:simulations,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data['geometry'] = json_decode($data['geometry'], true);
        $data['warna'] = $data['warna'] ?? '#1f6feb';
        $zone = Zone::create($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'entity' => 'zone',
            'entity_id' => $zone->id,
            'data' => ['nama' => $zone->nama, 'jenis' => $zone->jenis],
            'ip' => request()->ip(),
        ]);

        return redirect()->route('tactical.zones')->with('success', 'Zona "' . $zone->nama . '" ditambahkan.');
    }

    public function update(Request $request, Zone $zone)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:120',
            'jenis' => 'required|in:zona,route,objective',
            'warna' => 'nullable|string|max:7',
            'geometry' => 'required|json',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $data['geometry'] = json_decode($data['geometry'], true);
        $zone->update($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'entity' => 'zone',
            'entity_id' => $zone->id,
            'data' => ['nama' => $zone->nama],
            'ip' => request()->ip(),
        ]);

        return back()->with('success', 'Zona diperbarui.');
    }

    public function destroy(Zone $zone)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'entity' => 'zone',
            'entity_id' => $zone->id,
            'data' => ['nama' => $zone->nama],
            'ip' => request()->ip(),
        ]);

        $zone->delete();
        return back()->with('success', 'Zona dihapus.');
    }
}
