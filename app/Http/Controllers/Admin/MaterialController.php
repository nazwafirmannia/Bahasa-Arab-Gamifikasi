<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Level;
use App\Services\MaterialParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('level.stage')->latest();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('level_id')) {
            $query->where('id_level', $request->level_id);
        }

        $materials = $query->paginate(15)->withQueryString();
        $levels = Level::orderBy('level_order')->get(['id_level', 'title_level', 'id_stage']);
        
        return view('admin.materials.index', compact('materials', 'levels'));
    }

    public function create()
    {
        $levels = Level::orderBy('level_order')->get(['id_level', 'title_level']);
        return view('admin.materials.form', compact('levels'));
    }

    // Helper: Parse file dengan error handling
    private function parseUploadedFile($file, MaterialParser $parser)
    {
        try {
            // Simpan file dulu
            $path = $file->store('materials/files', 'public');
            $absolutePath = Storage::disk('public')->path($path);
            
            // Deteksi tipe file
            $ext = strtolower($file->getClientOriginalExtension());
            $fileType = in_array($ext, ['pdf']) ? 'pdf' : 'word';
            
            // Parse
            return [
                'path' => $path,
                'content' => $parser->parseFile($absolutePath, $fileType)
            ];
        } catch (\Exception $e) {
            Log::error("Parse Error: " . $e->getMessage());
            return [
                'path' => null,
                'content' => ['vocab_content' => '', 'grammar_content' => '', 'dialog_content' => '']
            ];
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'title' => 'required|string|max:255',
            'vocab_content' => 'nullable|string',
            'grammar_content' => 'nullable|string',
            'dialog_content' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'textbook_reference' => 'nullable|string|max:50',
            'vocab_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'grammar_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'dialog_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'xp_reward' => 'nullable|integer|min:0',
            'coin_reward' => 'nullable|integer|min:0',
            'is_global' => 'nullable|boolean',
            'global_category' => 'nullable|string|max:50',
        ]);

        $parser = new MaterialParser();
        
        // Handle vocab file
        if ($request->hasFile('vocab_file')) {
            $result = $this->parseUploadedFile($request->file('vocab_file'), $parser);
            if ($result['path']) {
                $validated['vocab_file'] = $result['path'];
                // Hanya timpa jika hasil parse tidak kosong
                if (!empty(trim($result['content']['vocab_content'] ?? ''))) {
                    $validated['vocab_content'] = $result['content']['vocab_content'];
                }
            }
        }

        // Handle grammar file
        if ($request->hasFile('grammar_file')) {
            $result = $this->parseUploadedFile($request->file('grammar_file'), $parser);
            if ($result['path']) {
                $validated['grammar_file'] = $result['path'];
                if (!empty(trim($result['content']['grammar_content'] ?? ''))) {
                    $validated['grammar_content'] = $result['content']['grammar_content'];
                }
            }
        }

        // Handle dialog file
        if ($request->hasFile('dialog_file')) {
            $result = $this->parseUploadedFile($request->file('dialog_file'), $parser);
            if ($result['path']) {
                $validated['dialog_file'] = $result['path'];
                if (!empty(trim($result['content']['dialog_content'] ?? ''))) {
                    $validated['dialog_content'] = $result['content']['dialog_content'];
                }
            }
        }

        Material::create($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', '✅ Materi berhasil disimpan!');
    }

    public function edit(Material $material)
    {
        $levels = Level::orderBy('level_order')->get(['id_level', 'title_level']);
        return view('admin.materials.form', compact('material', 'levels'));
    }

    // ✅ UPDATE METHOD YANG SUDAH DIPERBAIKI
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'title' => 'required|string|max:255',
            'vocab_content' => 'nullable|string',
            'grammar_content' => 'nullable|string',
            'dialog_content' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'textbook_reference' => 'nullable|string|max:50',
            'vocab_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'grammar_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'dialog_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'xp_reward' => 'nullable|integer|min:0',
            'coin_reward' => 'nullable|integer|min:0',
            'is_global' => 'nullable|boolean',
            'global_category' => 'nullable|string|max:50',
        ]);

        $parser = new MaterialParser();

        // Helper untuk update file + parse
        $updateFile = function($fileKey, $contentKey) use ($request, $parser, &$validated, $material) {
            if ($request->hasFile($fileKey)) {
                // Hapus file lama
                if ($material->$fileKey) {
                    Storage::disk('public')->delete($material->$fileKey);
                }
                
                $result = $this->parseUploadedFile($request->file($fileKey), $parser);
                if ($result['path']) {
                    $validated[$fileKey] = $result['path'];
                    if (!empty(trim($result['content'][$contentKey] ?? ''))) {
                        $validated[$contentKey] = $result['content'][$contentKey];
                    }
                }
            }
        };

        $updateFile('vocab_file', 'vocab_content');
        $updateFile('grammar_file', 'grammar_content');
        $updateFile('dialog_file', 'dialog_content');

        $material->update($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', '✅ Materi berhasil diperbarui!');
    }

    public function destroy(Material $material)
    {
        // Hapus semua file terkait
        foreach (['vocab_file', 'grammar_file', 'dialog_file', 'pdf_file'] as $fileKey) {
            if ($material->$fileKey) {
                Storage::disk('public')->delete($material->$fileKey);
            }
        }
        
        $material->questions()->delete();
        $material->delete();
        
        return redirect()->route('admin.materials.index')
            ->with('success', '🗑️ Materi berhasil dihapus!');
    }
}