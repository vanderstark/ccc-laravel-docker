<?php
namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('simulations')->get();
        return view('tactical.organizations', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:organizations,code',
            'nama' => 'required|string|max:150',
            'jenis' => 'required|in:polri,hankam,pemda,instansi',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Organization::create($data);
        return back()->with('success', 'Instansi ditambahkan.');
    }
}
