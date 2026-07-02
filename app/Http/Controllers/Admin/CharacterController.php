<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $query = Character::orderBy('unlock_level');
    
        if ($request->filled('search')) {
    
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }
    
        if ($request->filled('unlock_level')) {
    
            $query->where(
                'unlock_level',
                $request->unlock_level
            );
        }
    
        if ($request->filled('status')) {
    
            $query->where(
                'is_active',
                $request->status
            );
        }
    
        $characters = $query
            ->paginate(12)
            ->withQueryString();
    
        return view(
            'admin.characters.index',
            compact('characters')
        );
    }

    public function create()
    {
        return view('admin.characters.form');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'unlock_level' => 'required|integer|min:1',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'description' => 'nullable|string|max:500',
        'is_active' => 'nullable|boolean',
    ]);

    if ($request->hasFile('image')) {

        $validated['image'] = $request
            ->file('image')
            ->store('characters', 'public');
    }

    $validated['is_active'] = $request->has('is_active');

    Character::create($validated);

    return redirect()
        ->route('admin.characters.index')
        ->with('success', 'Karakter berhasil ditambahkan');
}

    public function edit(Character $character)
    {
        return view('admin.characters.form', compact('character'));
    }

    public function update(Request $request, Character $character)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'unlock_level' => 'required|integer|min:1',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'description' => 'nullable|string|max:500',
        'is_active' => 'nullable|boolean',
    ]);

    if ($request->hasFile('image')) {

        if (
            $character->image &&
            Storage::disk('public')->exists($character->image)
        ) {
            Storage::disk('public')->delete(
                $character->image
            );
        }

        $validated['image'] = $request
            ->file('image')
            ->store('characters', 'public');
    }

    $validated['is_active'] = $request->has('is_active');

    $character->update($validated);

    return redirect()
        ->route('admin.characters.index')
        ->with('success', 'Karakter berhasil diperbarui');
}

    public function destroy(Character $character)
    {
        // Hapus file gambar jika lokal
        if ($character->image && !filter_var($character->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($character->image);
        }
        $character->delete();
        return redirect()->route('admin.characters.index')->with('success', '🗑️ Karakter berhasil dihapus!');
    }
}