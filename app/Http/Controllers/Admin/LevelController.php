<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Stage;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $query = Level::with(['stage', 'materials'])->orderBy('level_order');
        if ($request->filled('stage_id')) $query->where('id_stage', $request->stage_id);
        if ($request->filled('search')) $query->where('title_level', 'like', '%'.$request->search.'%');
        $levels = $query->paginate(15)->withQueryString();
        $stages = Stage::orderBy('urutan')->get(['id_stage', 'stage_name']);
        return view('admin.levels.index', compact('levels', 'stages'));
    }

    public function create()
    {
        $stages = Stage::orderBy('urutan')->get(['id_stage', 'stage_name']);
        return view('admin.levels.form', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_stage' => 'required|exists:stage,id_stage',
            'title_level' => 'required|string|max:100',
            'level_order' => 'required|integer|min:1',
            'status_kunci' => 'boolean',
        ]);
        $validated['status_kunci'] = $request->has('status_kunci');
        Level::create($validated);
        return redirect()->route('admin.levels.index')->with('success', '✅ Level berhasil ditambahkan!');
    }

    public function edit(Level $level)
    {
        $stages = Stage::orderBy('urutan')->get(['id_stage', 'stage_name']);
        return view('admin.levels.form', compact('level', 'stages'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'id_stage' => 'required|exists:stage,id_stage',
            'title_level' => 'required|string|max:100',
            'level_order' => 'required|integer|min:1',
            'status_kunci' => 'boolean',
        ]);
        $validated['status_kunci'] = $request->has('status_kunci');
        $level->update($validated);
        return redirect()->route('admin.levels.index')->with('success', '✅ Level berhasil diperbarui!');
    }

    public function destroy(Level $level)
    {
        $level->materials()->delete();
        $level->quiz()->delete();
        $level->delete();
        return redirect()->route('admin.levels.index')->with('success', '🗑️ Level berhasil dihapus!');
    }
}