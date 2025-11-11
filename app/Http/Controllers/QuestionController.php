<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Display all FAQ questions
     */
    public function index(Request $request): View
    {
        $query = Question::with(['user', 'answers.user']);

        // Filter by status
        if ($request->has('filter')) {
            if ($request->filter === 'answered') {
                $query->has('answers');
            } elseif ($request->filter === 'unanswered') {
                $query->doesntHave('answers');
            } elseif ($request->filter === 'my_questions') {
                $query->where('user_id', auth()->id());
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where('text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(15);

        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new question
     */
    public function create(): View
    {
        return view('questions.create');
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'text' => 'required|string|min:10|max:1000',
        ]);

        Question::create([
            'text' => $request->text,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('questions.index')
            ->with('status', 'Question submitted successfully! An admin or worker will respond soon.');
    }

    /**
     * Display the specified question with its answers
     */
    public function show(Question $question): View
    {
        $question->load(['user', 'answers.user']);

        return view('questions.show', compact('question'));
    }

    /**
     * Store an answer to a question
     */
    public function storeAnswer(Request $request, Question $question): RedirectResponse
    {
        // Only admins and workers can answer questions
        if (!auth()->user()->hasRole([Role::Admin, Role::Worker])) {
            abort(403);
        }

        $request->validate([
            'text' => 'required|string|min:5|max:2000',
        ]);

        Answer::create([
            'text' => $request->text,
            'question_id' => $question->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('status', 'Answer posted successfully.');
    }

    /**
     * Delete a question (only by owner or admin)
     */
    public function destroy(Question $question): RedirectResponse
    {
        // Only the question owner or admin can delete
        if ($question->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $question->delete();

        return redirect()->route('questions.index')
            ->with('status', 'Question deleted successfully.');
    }

    /**
     * Delete an answer (only by owner or admin)
     */
    public function destroyAnswer(Question $question, Answer $answer): RedirectResponse
    {
        // Only the answer owner or admin can delete
        if ($answer->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $answer->delete();

        return back()->with('status', 'Answer deleted successfully.');
    }
}
