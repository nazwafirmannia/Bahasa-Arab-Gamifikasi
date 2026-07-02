<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index(Request $request)
    {
        $query = Stage::withCount('levels')->orderBy('urutan');
        if ($request->filled('search')) $query->where('stage_name', 'like', '%'.$request->search.'%');
        $stages = $query->paginate(15)->withQueryString();
        return view('admin.stages.index', compact('stages'));
    }

    public function create() { return view('admin.stages.form'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_name' => 'required|string|max:100',
            'urutan' => 'required|integer|unique:stage,urutan',
            'xp_multiplier' => 'required|numeric|min:0.1',
            'coin_multiplier' => 'required|numeric|min:0.1',
            'base_xp_material' => 'required|integer|min:0',
            'base_coin_material' => 'required|integer|min:0',
        ]);
        Stage::create($validated);
        return redirect()->route('admin.stages.index')->with('success', '✅ Stage berhasil ditambahkan!');
    }

    public function edit(Stage $stage) { return view('admin.stages.form', compact('stage')); }

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'stage_name' => 'required|string|max:100',
            'urutan' => 'required|integer|unique:stage,urutan,'.$stage->id_stage.',id_stage',
            'xp_multiplier' => 'required|numeric|min:0.1',
            'coin_multiplier' => 'required|numeric|min:0.1',
            'base_xp_material' => 'required|integer|min:0',
            'base_coin_material' => 'required|integer|min:0',
        ]);
        $stage->update($validated);
        return redirect()->route('admin.stages.index')->with('success', '✅ Stage berhasil diperbarui!');
    }

    public function destroy(Stage $stage)
    {
        $stage->levels()->delete(); // Cascade
        $stage->delete();
        return redirect()->route('admin.stages.index')->with('success', '🗑️ Stage berhasil dihapus!');
    }
}