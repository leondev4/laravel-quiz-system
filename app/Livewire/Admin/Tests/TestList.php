<?php

namespace App\Livewire\Admin\Tests;

use App\Models\Quiz;
use App\Models\Test;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TestList extends Component
{
    use WithPagination;

    public $quiz_id = 0;

    public function mount()
    {
        // Existing mount logic
    }

    public function updatedQuizId()
    {
        $this->resetPage();
    }

    public function deleteTest($testId)
    {
        $test = Test::findOrFail($testId);

        // Optional: Add authorization check
        // $this->authorize('delete', $test);

        $test->delete();

        session()->flash('message', 'Quiz elminado satisfactoriamente.');

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

        $deletedCount = $query->count();
        $query->delete();

        session()->flash('message', "Eliminados {$deletedCount} quizzes mayores {$days} días.");

        $this->resetPage();

        // $cutoffDate = Carbon::now()->subDays($days);

        // $query = Test::where('created_at', '<', $cutoffDate);

        // // Apply quiz filter if selected
        // if ($this->quiz_id > 0) {
        //     $query->where('quiz_id', $this->quiz_id);
        // }

        // $deletedCount = $query->count();
        // $query->delete();

        // session()->flash('message', "Eliminados {$deletedCount} quizzes mayores {$days} días.");

        // // Reset pagination
        // $this->resetPage();
    }

    public function render()
    {

        $user = auth()->user();
        // dd($user);
        // Obtener los quizzes creados por el usuario actual
        $quizIds = Quiz::where('user_id', $user?->id)->pluck('id');

        // Filtrar los tests que pertenecen a esos quizzes
        $query = Test::with(['user', 'quiz'])
            ->whereIn('quiz_id', $quizIds)
            ->withCount('questions')
            ->latest();

        if ($this->quiz_id > 0) {
            $query->where('quiz_id', $this->quiz_id);
        }

        $tests = $query->paginate(15);
        $quizzes = Quiz::whereIn('id', $quizIds)->orderBy('title')->get();

        return view('livewire.admin.tests.test-list', [
            'tests' => $tests,
            'quizzes' => $quizzes,
        ]);

        // $query = Test::with(['user', 'quiz'])
        //     ->withCount('questions')
        // ->latest();

        // if ($this->quiz_id > 0) {
        //     $query->where('quiz_id', $this->quiz_id);
        // }

        // $tests = $query->paginate(15);
        // $quizzes = Quiz::orderBy('title')->get();

        // return view('livewire.admin.tests.test-list', [
        //     'tests' => $tests,
        //     'quizzes' => $quizzes,
        // ]);
    }
}
