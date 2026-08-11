<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->entity, fn ($q) => $q->where('entity', $request->entity))
            ->when($request->action, fn ($q) => $q->where('action', $request->action))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(30);

        return view('tactical.audit', compact('logs'));
    }
}
