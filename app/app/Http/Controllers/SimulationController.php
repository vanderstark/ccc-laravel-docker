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
        return redirect()->route('simulations.show', $sim);
    }

    public function show(\App\Models\Simulation $simulation): View
    {
        return view('simulations.show', compact('simulation'));
    }

    public function destroy(\App\Models\Simulation $simulation): RedirectResponse
    {
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