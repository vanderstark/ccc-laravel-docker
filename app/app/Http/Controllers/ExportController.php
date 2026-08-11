<?php
namespace App\Http\Controllers;

use App\Models\Simulation;
use App\Services\ExportService;

class ExportController extends Controller
{
    public function simulationCsv()
    {
        $simulations = Simulation::with('disasterType')->latest()->get();
        return app(ExportService::class)->simulationCsv($simulations);
    }

    public function briefing(Simulation $simulation)
    {
        $md = app(ExportService::class)->briefingMarkdown($simulation);
        $filename = 'briefing-sim-' . $simulation->id . '-' . now()->format('Ymd-His') . '.md';
        return response($md, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
