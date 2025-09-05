<?php

namespace App\Livewire\Admin\Tests;

use App\Models\Quiz;
use App\Models\Test;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TestList extends Component
{
    use WithPagination;

    public $quiz_id = 0;
    public $subject_id = '';

    public function mount()
    {
        // Existing mount logic
    }

    public function updatedQuizId()
    {
        $this->resetPage();
    }

    public function updatedSubjectId()
    {
        // Resetear el quiz_id cuando cambia la materia
        $this->quiz_id = 0;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->quiz_id = 0;
        $this->subject_id = '';
        $this->resetPage();
    }

    public function deleteTest($testId)
    {
        $test = Test::findOrFail($testId);

        // Optional: Add authorization check
        // $this->authorize('delete', $test);

        $test->delete();

        session()->flash('message', 'Quiz eliminado satisfactoriamente.');

        // Reset pagination if needed
        $this->resetPage();
    }

    public function deleteOldTests($days)
    {
        $user = auth()->user();
        $cutoffDate = Carbon::now()->subDays($days);

        // Obtener los quizzes creados por el usuario actual
        $quizIds = Quiz::where('user_id', $user?->id)->pluck('id');
        
        // Solo eliminar tests de quizzes del usuario actual
        $query = Test::where('created_at', '<', $cutoffDate)
            ->whereIn('quiz_id', $quizIds);

        // Aplicar filtro de quiz si está seleccionado
        if ($this->quiz_id > 0) {
            $query->where('quiz_id', $this->quiz_id);
        }

        // Aplicar filtro de materia si está seleccionado
        if ($this->subject_id) {
            $query->whereHas('quiz', function ($q) {
                $q->where('subject_id', $this->subject_id);
            });
        }

        $deletedCount = $query->count();
        $query->delete();

        session()->flash('message', "Eliminados {$deletedCount} quizzes mayores {$days} días.");

        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        
        // Obtener los quizzes creados por el usuario actual
        $quizIds = Quiz::where('user_id', $user?->id)->pluck('id');

        // Filtrar los tests que pertenecen a esos quizzes
        $query = Test::with(['user', 'quiz', 'quiz.subject'])
            ->whereIn('quiz_id', $quizIds)
            ->withCount('questions')
            ->latest();

        // Aplicar filtro por quiz específico
        if ($this->quiz_id > 0) {
            $query->where('quiz_id', $this->quiz_id);
        }

        // Aplicar filtro por materia
        if ($this->subject_id) {
            $query->whereHas('quiz', function ($q) {
                $q->where('subject_id', $this->subject_id);
            });
        }

        $tests = $query->paginate(15);
        
        // Obtener quizzes del usuario para el filtro - FILTRAR POR MATERIA SI ESTÁ SELECCIONADA
        $quizzesQuery = Quiz::whereIn('id', $quizIds);
        
        // Si hay una materia seleccionada, filtrar los quizzes por esa materia
        if ($this->subject_id) {
            $quizzesQuery->where('subject_id', $this->subject_id);
        }
        
        $quizzes = $quizzesQuery->orderBy('title')->get();
        
        // Obtener materias que tienen quizzes del usuario actual
        $subjects = Subject::active()
            ->whereHas('quizzes', function ($query) use ($quizIds) {
                $query->whereIn('id', $quizIds);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.tests.test-list', [
            'tests' => $tests,
            'quizzes' => $quizzes,
            'subjects' => $subjects,
        ]);
    }
}
