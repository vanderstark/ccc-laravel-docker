<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marker;
use App\Models\Zone;
use App\Models\Simulation;
use Illuminate\Http\Request;

class TacticalApiController extends Controller
{
    /** Live sync: kirim semua marker & zone aktif. */
    public function sync()
    {
        $markers = Marker::where('status', 'active')
            ->when(request('simulation_id'), function ($q) {
                $q->where('simulation_id', request('simulation_id'));
            })
            ->limit(100)
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id, 'type' => $m->type, 'nama' => $m->nama,
                'lat' => $m->lat, 'lon' => $m->lon,
                'status' => $m->status, 'kategori' => $m->kategori,
                'updated_at' => $m->updated_at?->toIso8601String(),
            ]);

        $zones = Zone::limit(50)->get()->map(fn ($z) => [
            'id' => $z->id, 'nama' => $z->nama, 'jenis' => $z->jenis,
            'warna' => $z->warna, 'geometry' => $z->geometry,
        ]);

        return response()->json(['markers' => $markers, 'zones' => $zones, 'timestamp' => now()->toIso8601String()]);
    }

    /** Replay: snapshot marker/zone pada waktu tertentu. */
    public function replay(Request $request, ?Simulation $simulation = null)
    {
        $time = $request->query('at', now()->toIso8601String());

        $markers = Marker::where('updated_at', '<=', $time)
            ->when($simulation, fn ($q) => $q->where('simulation_id', $simulation->id))
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id, 'type' => $m->type, 'nama' => $m->nama,
                'lat' => $m->lat, 'lon' => $m->lon, 'status' => $m->status,
                'updated_at' => $m->updated_at?->toIso8601String(),
            ]);

        return response()->json([
            'at' => $time,
            'markers' => $markers,
            'simulation' => $simulation ? $simulation->only('id', 'location') : null,
        ]);
    }

    /** Timeline snapshot — semua aksi audit log untuk replay. */
    public function timeline(?Simulation $simulation = null)
    {
        $logs = \App\Models\AuditLog::query()
            ->when($simulation, fn ($q) => $q->where('entity_id', $simulation->id))
            ->orderBy('created_at')
            ->limit(100)
            ->get()
            ->map(fn ($l) => [
                'id' => $l->id, 'action' => $l->action,
                'entity' => $l->entity, 'entity_id' => $l->entity_id,
                'user' => $l->user?->name ?? 'System',
                'created_at' => $l->created_at?->toIso8601String(),
            ]);

        return response()->json(['timeline' => $logs]);
    }
}
