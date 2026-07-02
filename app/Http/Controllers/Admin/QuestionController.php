<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['material.level'])->orderByDesc('id_question');
        
        if ($request->filled('search')) $query->where('question_text', 'like', '%' . $request->search . '%');
        if ($request->filled('difficulty')) $query->where('difficulty', $request->difficulty);
        if ($request->filled('material_id')) $query->where('id_material', $request->material_id);

        $questions = $query->paginate(15)->withQueryString();
        
        // ✅ FIX: Ambil materi + relasi level dengan benar
        $materials = Material::with('level:id_level,title_level')->orderBy('order')->get();
        
        return view('admin.questions.index', compact('questions', 'materials'));
    }

    public function create()
    {
        // ✅ FIX: Same as above
        $materials = Material::with('level:id_level,title_level')->orderBy('order')->get();
        return view('admin.questions.form', compact('materials'));
    }

    public function edit(Question $question)
    {
        // ✅ FIX: Same as above
        $materials = Material::with('level:id_level,title_level')->orderBy('order')->get();
        return view('admin.questions.form', compact('question', 'materials'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'id_material' => 'required|exists:material,id_material',
        'question_text' => 'required|string',
        'question_type' => 'required|in:mcq,fill',
        'option_a' => 'nullable|string|max:255',
        'option_b' => 'nullable|string|max:255',
        'option_c' => 'nullable|string|max:255',
        'option_d' => 'nullable|string|max:255',
        'correct_answer' => 'required|string',
        'explanation' => 'nullable|string',
        'difficulty' => 'required|in:easy,medium,hard',
        'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
    ]);

    if ($request->hasFile('pdf_file')) {
        $validated['pdf_file'] = $request->file('pdf_file')->store('questions/pdf', 'public');
    }

    Question::create($validated);
    return redirect()->route('admin.questions.index')->with('success', '✅ Soal berhasil ditambahkan!');
}

public function update(Request $request, Question $question)
{
    $validated = $request->validate([
        'id_material' => 'required|exists:material,id_material',
        'question_text' => 'required|string',
        'question_type' => 'required|in:mcq,fill',
        'option_a' => 'nullable|string|max:255',
        'option_b' => 'nullable|string|max:255',
        'option_c' => 'nullable|string|max:255',
        'option_d' => 'nullable|string|max:255',
        'correct_answer' => 'required|string',
        'explanation' => 'nullable|string',
        'difficulty' => 'required|in:easy,medium,hard',
        'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
    ]);

    if ($request->hasFile('pdf_file')) {
        if ($question->pdf_file) Storage::disk('public')->delete($question->pdf_file);
        $validated['pdf_file'] = $request->file('pdf_file')->store('questions/pdf', 'public');
    }

    $question->update($validated);
    return redirect()->route('admin.questions.index')->with('success', '✅ Soal berhasil diperbarui!');
}

public function destroy(Question $question)
{
    if ($question->pdf_file) Storage::disk('public')->delete($question->pdf_file);
    $question->delete();
    return redirect()->route('admin.questions.index')->with('success', '🗑️ Soal dihapus!');
}
}