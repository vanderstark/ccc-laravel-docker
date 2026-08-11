<?php
namespace App\Http\Controllers;

use App\Models\Preset;
use App\Models\Simulation;
use App\Services\SimulationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private SimulationService $simSvc) {}

    public function index(): View
    {
        $stats = $this->simSvc->stats();
        return view('dashboard', $stats);
    }

    public function maps(): View
    {
        $presets = Preset::all();
        // Simulasi dengan koordinat untuk ditampilkan di peta
        $simulations = Simulation::with('disasterType')
            ->whereNotNull('lat')->whereNotNull('lon')
            ->latest()->limit(200)->get();
        return view('maps', compact('presets', 'simulations'));
    }
}