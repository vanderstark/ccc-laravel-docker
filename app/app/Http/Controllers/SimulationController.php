<?php
namespace App\Http\Controllers;

use App\Http\Requests\SimulationRequest;
use App\Models\DisasterType;
use App\Models\War;
use App\Models\Preset;
use App\Services\SimulationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SimulationController extends Controller
{
    public function __construct(private SimulationService $simSvc) {}

    public function index(): View
    {
        $disasterTypes = DisasterType::orderBy('kategori')->get();
        $presets = Preset::all();
        $wars = War::all();
        return view('simulations.index', compact('disasterTypes', 'presets', 'wars'));
    }

    public function create(): View
    {
        $disasterTypes = DisasterType::orderBy('kategori')->get();
        $presets = Preset::all();
        $wars = War::all();
        return view('simulations.create', compact('disasterTypes', 'presets', 'wars'));
    }

    public function store(SimulationRequest $request, SimulationService $simSvc): RedirectResponse
    {
        $input = $request->validated();
        $sim = $simSvc->run($input);

        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'entity' => 'simulation',
            'entity_id' => $sim->id,
            'data' => ['disaster_type' => $sim->disaster_type, 'location' => $sim->location, 'alert' => $sim->alert_level],
            'ip' => $request->ip(),
        ]);

        return redirect()->route('simulations.show', $sim);
    }

    public function show(\App\Models\Simulation $simulation): View
    {
        return view('simulations.show', compact('simulation'));
    }

    public function destroy(\App\Models\Simulation $simulation): RedirectResponse
    {
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'entity' => 'simulation',
            'entity_id' => $simulation->id,
            'data' => ['location' => $simulation->location],
            'ip' => request()->ip(),
        ]);

        $simulation->delete();
        return back()->with('success', 'Simulasi berhasil dihapus.');
    }

    public function history(Request $request): View
    {
        $simulations = \App\Models\Simulation::with(['disasterType', 'preset', 'war'])
            ->when($request->filled('search'), fn($q) => $q->where('location', 'like', "%{$request->search}%"))
            ->latest()->paginate(20)->withQueryString();
        return view('simulations.history', compact('simulations'));
    }
}