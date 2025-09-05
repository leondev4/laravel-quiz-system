<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class QuizList extends Component
{
    use WithPagination;

    public $search = '';
    public $subject_id = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatingSubjectId()
    {
        $this->resetPage();
    }

    public function updatedSubjectId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->subject_id = '';
        $this->resetPage();
    }

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
        $user = auth()->user();

        $quizzes = Quiz::where('user_id', $user->id)
            ->with(['subject']) // Cargar la relación con materia
            ->withCount('questions')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->subject_id, function ($query) {
                $query->where('subject_id', $this->subject_id);
            })
            ->latest()
            ->paginate(15);

        // Obtener materias que tienen quizzes del usuario actual
        $subjects = Subject::active()
            ->whereHas('quizzes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.quiz.quiz-list', compact('quizzes', 'subjects'));
    }
}
