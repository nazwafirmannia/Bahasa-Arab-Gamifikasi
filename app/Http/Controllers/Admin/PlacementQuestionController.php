<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementQuestion;
use Illuminate\Http\Request;

class PlacementQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = PlacementQuestion::query();

        // Search
        if ($request->filled('search')) {
            $query->where(
                'question_text',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Difficulty
        if ($request->filled('difficulty')) {
            $query->where(
                'difficulty',
                $request->difficulty
            );
        }

        // Status
        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status
            );
        }

        $questions = $query
            ->orderByDesc('id_question')
            ->paginate(12);

        $stats = [
            'total' => PlacementQuestion::count(),

            'easy' => PlacementQuestion::where(
                'difficulty',
                'easy'
            )->count(),

            'medium' => PlacementQuestion::where(
                'difficulty',
                'medium'
            )->count(),

            'hard' => PlacementQuestion::where(
                'difficulty',
                'hard'
            )->count(),
        ];

        return view(
            'admin.placement.index',
            compact(
                'questions',
                'stats'
            )
        );
    }

    public function create()
    {
        return view(
            'admin.placement.form'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'question_text' => 'required',

            'option_a' => 'required',

            'option_b' => 'required',

            'option_c' => 'required',

            'option_d' => 'required',

            'correct_answer' =>
                'required|in:A,B,C,D',

            'difficulty' =>
                'required|in:easy,medium,hard',

        ]);

        PlacementQuestion::create([

            'question_text' =>
                $validated['question_text'],

            'option_a' =>
                $validated['option_a'],

            'option_b' =>
                $validated['option_b'],

            'option_c' =>
                $validated['option_c'],

            'option_d' =>
                $validated['option_d'],

            'correct_answer' =>
                $validated['correct_answer'],

            'difficulty' =>
                $validated['difficulty'],

            'explanation' =>
                $request->explanation,

            'is_active' =>
                $request->has('is_active'),

        ]);

        return redirect()
            ->route('admin.placement.index')
            ->with(
                'success',
                'Soal placement berhasil ditambahkan.'
            );
    }

    public function show(PlacementQuestion $placement)
    {
        return redirect()
            ->route(
                'admin.placement.edit',
                $placement
            );
    }

    public function edit(PlacementQuestion $placement)
    {
        $question = $placement;

        return view(
            'admin.placement.form',
            compact('question')
        );
    }

    public function update(
        Request $request,
        PlacementQuestion $placement
    )
    {
        $validated = $request->validate([

            'question_text' => 'required',

            'option_a' => 'required',

            'option_b' => 'required',

            'option_c' => 'required',

            'option_d' => 'required',

            'correct_answer' =>
                'required|in:A,B,C,D',

            'difficulty' =>
                'required|in:easy,medium,hard',

        ]);

        $placement->update([

            'question_text' =>
                $validated['question_text'],

            'option_a' =>
                $validated['option_a'],

            'option_b' =>
                $validated['option_b'],

            'option_c' =>
                $validated['option_c'],

            'option_d' =>
                $validated['option_d'],

            'correct_answer' =>
                $validated['correct_answer'],

            'difficulty' =>
                $validated['difficulty'],

            'explanation' =>
                $request->explanation,

            'is_active' =>
                $request->has('is_active'),

        ]);

        return redirect()
            ->route('admin.placement.index')
            ->with(
                'success',
                'Soal placement berhasil diperbarui.'
            );
    }

    public function destroy(
        PlacementQuestion $placement
    )
    {
        $placement->delete();

        return redirect()
            ->route('admin.placement.index')
            ->with(
                'success',
                'Soal placement berhasil dihapus.'
            );
    }
}