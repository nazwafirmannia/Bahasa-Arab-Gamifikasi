<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizItem;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizItemController extends Controller
{
    public function index(Request $request)
    {
        $query = QuizItem::with('quiz')->orderBy('order_index');
        
        if ($request->filled('quiz_id')) {
            $query->where('id_quiz', $request->quiz_id);
        }
        
        $items = $query->paginate(20)->withQueryString();
        $quizzes = Quiz::orderByDesc('id_quiz')->get(['id_quiz', 'title']);
        
        return view('admin.quiz-items.index', compact('items', 'quizzes'));
    }

    public function create()
    {
        $quizzes = Quiz::orderByDesc('id_quiz')->get(['id_quiz', 'title']);
        return view('admin.quiz-items.form', compact('quizzes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_quiz' => 'required|exists:quizzes,id_quiz', // ✅ Pastikan nama tabel plural
            'item_type' => 'required|in:mcq,fill,flashcard', // ✅ Tambah flashcard
            'question_text' => 'required|string',
            'option_a' => 'nullable|string|max:255',
            'option_b' => 'nullable|string|max:255',
            'option_c' => 'nullable|string|max:255',
            'option_d' => 'nullable|string|max:255',
            'correct_answer' => 'required|string',
            'order_index' => 'required|integer|min:1',
            
            // ✅ Field khusus flashcard
            'flashcard_back' => 'nullable|string|max:255',
            'flashcard_audio_front' => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
            'flashcard_audio_back' => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
            'flashcard_image' => 'nullable|file|mimes:jpg,png,webp|max:2048',
        ]);

        // ✅ Bangun game_data khusus flashcard
        $gameData = null;
        if ($request->item_type === 'flashcard') {
            $gameData = [
                'back_text' => $request->flashcard_back ?? '',
                'audio_front' => null,
                'audio_back' => null,
                'image' => null,
            ];

            // Handle upload file (jika ada)
            if ($request->hasFile('flashcard_audio_front')) {
                $gameData['audio_front'] = $request->file('flashcard_audio_front')->store('public/flashcards/audio');
            }
            if ($request->hasFile('flashcard_audio_back')) {
                $gameData['audio_back'] = $request->file('flashcard_audio_back')->store('public/flashcards/audio');
            }
            if ($request->hasFile('flashcard_image')) {
                $gameData['image'] = $request->file('flashcard_image')->store('public/flashcards/images');
            }

            // ✅ Override correct_answer jadi 'known' untuk flashcard
            $validated['correct_answer'] = 'known';
        }

        $validated['game_data'] = $gameData; // Laravel otomatis convert ke JSON jika Model sudah di-cast
        
        QuizItem::create($validated);
        return redirect()->route('admin.quiz-items.index')->with('success', '✅ Soal Quiz berhasil ditambahkan!');
    }

    public function edit(QuizItem $quizItem)
    {
        $quizzes = Quiz::orderByDesc('id_quiz')->get(['id_quiz', 'title']);
        return view('admin.quiz-items.form', compact('quizItem', 'quizzes'));
    }

    public function update(Request $request, QuizItem $quizItem)
    {
        $validated = $request->validate([
            'id_quiz' => 'required|exists:quizzes,id_quiz',
            'item_type' => 'required|in:mcq,fill,flashcard',
            'question_text' => 'required|string',
            'option_a' => 'nullable|string|max:255',
            'option_b' => 'nullable|string|max:255',
            'option_c' => 'nullable|string|max:255',
            'option_d' => 'nullable|string|max:255',
            'correct_answer' => 'required|string',
            'order_index' => 'required|integer|min:1',
            
            'flashcard_back' => 'nullable|string|max:255',
            'flashcard_audio_front' => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
            'flashcard_audio_back' => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
            'flashcard_image' => 'nullable|file|mimes:jpg,png,webp|max:2048',
        ]);

        $gameData = $quizItem->game_data ?? []; // Ambil data lama jika ada
        if ($request->item_type === 'flashcard') {
            $gameData['back_text'] = $request->flashcard_back ?? '';
            
            if ($request->hasFile('flashcard_audio_front')) {
                // Hapus file lama jika ada
                if (!empty($gameData['audio_front'])) Storage::delete($gameData['audio_front']);
                $gameData['audio_front'] = $request->file('flashcard_audio_front')->store('public/flashcards/audio');
            }
            if ($request->hasFile('flashcard_audio_back')) {
                if (!empty($gameData['audio_back'])) Storage::delete($gameData['audio_back']);
                $gameData['audio_back'] = $request->file('flashcard_audio_back')->store('public/flashcards/audio');
            }
            if ($request->hasFile('flashcard_image')) {
                if (!empty($gameData['image'])) Storage::delete($gameData['image']);
                $gameData['image'] = $request->file('flashcard_image')->store('public/flashcards/images');
            }

            $validated['correct_answer'] = 'known';
        }

        $validated['game_data'] = $gameData;
        $quizItem->update($validated);
        
        return redirect()->back()->with('success', 'Soal quiz berhasil diupdate!');
    }

    public function destroy(QuizItem $quizItem)
    {
        // Hapus file terkait jika ada
        if ($quizItem->game_data) {
            $files = array_filter($quizItem->game_data);
            foreach ($files as $path) {
                Storage::delete($path);
            }
        }
        
        $quizItem->delete();
        return redirect()->route('admin.quiz-items.index')->with('success', '🗑️ Soal Quiz berhasil dihapus!');
    }
}