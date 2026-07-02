<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with(['level.stage', 'items'])->orderByDesc('id_quiz');
        if ($request->filled('level_id')) $query->where('id_level', $request->level_id);
        $quizzes = $query->paginate(15)->withQueryString();
        $levels = Level::with('stage')->orderBy('level_order')->get(['id_level', 'title_level', 'id_stage']);
        return view('admin.quizzes.index', compact('quizzes', 'levels'));
    }

    public function create()
    {
        $levels = Level::orderBy('level_order')->get(['id_level', 'title_level']);
        return view('admin.quizzes.form', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'title' => 'required|string|max:150',
            'quiz_format' => 'required|string',
            'passing_score' => 'required|integer',
            'time_limit_sec' => 'nullable|integer',
            'max_attempts' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);
    
        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('quiz/pdf', 'public');
        }
    
        Quiz::create($validated);
        return redirect()->route('admin.quizzes.index')->with('success', '✅ Quiz berhasil ditambahkan!');
    }

    public function edit(Quiz $quiz)
    {
        $levels = Level::orderBy('level_order')->get(['id_level', 'title_level']);
        return view('admin.quizzes.form', compact('quiz', 'levels'));
    }

    public function update(Request $request, Quiz $quiz)
{
    $validated = $request->validate([
        'id_level' => 'required|exists:level,id_level',
        'title' => 'required|string|max:150',
        'quiz_format' => 'required|string',
        'passing_score' => 'required|integer',
        'time_limit_sec' => 'nullable|integer',
        'max_attempts' => 'required|integer',
        'status' => 'required|in:active,inactive',
        'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
    ]);

    if ($request->hasFile('pdf_file')) {
        if ($quiz->pdf_file) Storage::disk('public')->delete($quiz->pdf_file);
        $validated['pdf_file'] = $request->file('pdf_file')->store('quiz/pdf', 'public');
    }
    $quiz->update($validated);
    return redirect()->route('admin.quizzes.index')->with('success', '✅ Quiz berhasil diperbarui!');
}

public function destroy(Quiz $quiz)
{
    if ($quiz->pdf_file) Storage::disk('public')->delete($quiz->pdf_file);
    $quiz->items()->delete();
    $quiz->delete();
    return redirect()->route('admin.quizzes.index')->with('success', '🗑️ Quiz dihapus!');
}
}