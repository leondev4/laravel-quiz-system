<?php

namespace App\Livewire\Question;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class QuestionList extends Component
{
    use WithPagination;

    public $search = '';
    public $subject_id = '';

    protected $listeners = ['refreshQuestions' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        // Pequeño delay antes de disparar el evento
        $this->js('setTimeout(() => { $dispatch("questions-filtered") }, 100)');
    }

    public function updatingSubjectId()
    {
        $this->resetPage();
    }

    public function updatedSubjectId()
    {
        // Pequeño delay antes de disparar el evento
        $this->js('setTimeout(() => { $dispatch("questions-filtered") }, 100)');
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->subject_id = '';
        $this->resetPage();
        
        // Disparar evento después de limpiar filtros
        $this->js('setTimeout(() => { $dispatch("questions-filtered") }, 150)');
    }

    public function delete(Question $question)
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN, 403);

        $user = auth()->user();

        // Solo permitir eliminar si la pregunta pertenece al usuario actual
        if ($question->user_id !== $user?->id) {
            abort(403, 'No autorizado');
        }
        
        $question->delete();
        
        // Disparar evento para re-renderizar KaTeX después de eliminar
        $this->js('setTimeout(() => { $dispatch("questions-filtered") }, 100)');
    }

    public function render(): View
    {
        $user = auth()->user();
        
        $questions = Question::where('user_id', $user?->id)
            ->with('subject')
            ->when($this->search, function ($query) {
                $query->where('text', 'like', '%' . $this->search . '%');
            })
            ->when($this->subject_id, function ($query) {
                $query->where('subject_id', $this->subject_id);
            })
            ->latest()
            ->paginate(15);

        $subjects = Subject::active()
            ->whereHas('questions', function ($query) use ($user) {
                $query->where('user_id', $user?->id);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.question.question-list', compact('questions', 'subjects'));
    }
}
