<?php
namespace App\Livewire\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

class QuestionManager extends Component
{
    use WithPagination;

    public Quiz $quiz;
    
    // Search and filter properties
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    
    // Selection properties
    public array $selectedQuestions = [];
    public array $currentQuizQuestions = [];
    
    // UI state
    public bool $showAddModal = false;
    
    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;
        $this->loadCurrentQuizQuestions();
    }
    
    public function loadCurrentQuizQuestions()
    {
        $this->currentQuizQuestions = $this->quiz->questions->pluck('id')->toArray();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedDateFrom()
    {
        $this->resetPage();
    }
    
    public function updatedDateTo()
    {
        $this->resetPage();
    }
    
    #[Computed]
    public function availableQuestions()
    {
        $query = Question::query()
            ->with(['options' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->whereNotIn('id', $this->currentQuizQuestions);
            
        if ($this->search) {
            $query->where('text', 'like', '%' . $this->search . '%');
        }
        
        // Filter by answer (options) dates
        if ($this->dateFrom || $this->dateTo) {
            $query->whereHas('options', function ($optionQuery) {
                if ($this->dateFrom) {
                    $optionQuery->whereDate('created_at', '>=', $this->dateFrom);
                }
                if ($this->dateTo) {
                    $optionQuery->whereDate('created_at', '<=', $this->dateTo);
                }
            });
        }
        
        // Order by the most recent answer date
        $query->addSelect([
            'latest_answer_date' => \App\Models\Option::select('created_at')
                ->whereColumn('question_id', 'questions.id')
                ->orderBy('created_at', 'desc')
                ->limit(1)
        ])->orderBy('latest_answer_date', 'desc');
        
        return $query->paginate(10);
    }
    
    #[Computed]
    public function currentQuestions()
    {
        return $this->quiz->questions()
            ->with(['options' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->addSelect([
                'questions.*',
                'latest_answer_date' => \App\Models\Option::select('created_at')
                    ->whereColumn('question_id', 'questions.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
            ])
            ->orderBy('latest_answer_date', 'desc')
            ->get();
    }
    
    public function toggleQuestionSelection($questionId)
    {
        if (in_array($questionId, $this->selectedQuestions)) {
            $this->selectedQuestions = array_diff($this->selectedQuestions, [$questionId]);
        } else {
            $this->selectedQuestions[] = $questionId;
        }
    }
    
    public function selectAllVisible()
    {
        $visibleQuestionIds = $this->availableQuestions->pluck('id')->toArray();
        $this->selectedQuestions = array_unique(array_merge($this->selectedQuestions, $visibleQuestionIds));
    }
    
    public function clearSelection()
    {
        $this->selectedQuestions = [];
    }
    
    public function addSelectedQuestions()
    {
        if (empty($this->selectedQuestions)) {
            session()->flash('error', 'No questions selected.');
            return;
        }
        
        $this->quiz->questions()->attach($this->selectedQuestions);
        $this->loadCurrentQuizQuestions();
        $this->selectedQuestions = [];
        $this->showAddModal = false;
        
        session()->flash('success', count($this->selectedQuestions) . ' questions added to quiz.');
    }
    
    public function removeQuestion($questionId)
    {
        $this->quiz->questions()->detach($questionId);
        $this->loadCurrentQuizQuestions();
        
        session()->flash('success', 'Question removed from quiz.');
    }
    
    public function openAddModal()
    {
        $this->showAddModal = true;
        $this->selectedQuestions = [];
    }
    
    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->selectedQuestions = [];
    }
    
    public function clearFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }
    
    public function render()
    {
        return view('livewire.quiz.question-manager');
    }
}