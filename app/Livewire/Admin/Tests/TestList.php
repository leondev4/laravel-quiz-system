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
        $cutoffDate = Carbon::now()->subDays($days);
        
        $query = Test::where('created_at', '<', $cutoffDate);
        
        // Apply quiz filter if selected
        if ($this->quiz_id > 0) {
            $query->where('quiz_id', $this->quiz_id);
        }
        
        $deletedCount = $query->count();
        $query->delete();
        
        session()->flash('message', "Eliminados {$deletedCount} quizzes mayores {$days} días.");
        
        // Reset pagination
        $this->resetPage();
    }

    public function render()
    {
        $query = Test::with(['user', 'quiz'])->latest();

        if ($this->quiz_id > 0) {
            $query->where('quiz_id', $this->quiz_id);
        }

        $tests = $query->paginate(15);
        $quizzes = Quiz::orderBy('title')->get();

        return view('livewire.admin.tests.test-list', [
            'tests' => $tests,
            'quizzes' => $quizzes,
        ]);
    }
}