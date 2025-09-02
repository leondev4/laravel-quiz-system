<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class QuizList extends Component
{
    public function delete($quiz_id)
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN, 403);

        Quiz::find($quiz_id)->delete();
    }

    public function togglePublished($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->update(['published' => !$quiz->published]);
        
        session()->flash('message', 'Quiz ' . ($quiz->published ? 'published' : 'unpublished') . ' successfully.');
    }

    public function togglePublic($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->update(['public' => !$quiz->public]);
        
        session()->flash('message', 'Quiz visibility changed successfully.');
    }

    public function render(): View
    {
        $quizzes = Quiz::
        where('user_id',auth()->id())->
        withCount('questions')->latest()->paginate();
        // dd($quizzes);
        return view('livewire.quiz.quiz-list', compact('quizzes'));
    }
}
