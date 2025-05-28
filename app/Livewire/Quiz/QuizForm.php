<?php

namespace App\Livewire\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Str;

class QuizForm extends Component
{
    public Quiz $quiz;

    // Add these properties for explicit binding
    public string $title = '';
    public string $slug = '';
    public ?string $description = '';
    public bool $published = false;
    public bool $public = false;

    public array $questions = [];

    public bool $editing = false;

    public array $listsForFields = [];

    protected $rules = [
        'title' => 'required|string',
        'slug' => 'string',
        'description' => 'nullable|string',
        'published' => 'boolean',
        'public' => 'boolean',
        'questions' => 'nullable|array',
    ];

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;

        if ($this->quiz->exists) {
            $this->editing = true;
            
            // Fill the properties from the model
            $this->title = $this->quiz->title;
            $this->slug = $this->quiz->slug;
            $this->description = $this->quiz->description ?? '';
            $this->published = $this->quiz->published;
            $this->public = $this->quiz->public;
            
            // Convert to array of strings for proper binding
            $this->questions = $this->quiz->questions()->pluck('id')->map(function($id) {
                return (string) $id;
            })->toArray();

        } else {
            $this->published = false;
            $this->public = false;
            $this->questions = [];
        }

        // Initialize lists after setting up the quiz data
        $this->initListsForFields();
    }

    public function updatedTitle(): void
    {
        $this->slug = Str::slug($this->title);
    }

    public function save()
    {
        $this->validate();

        // Update the model with the form data
        $this->quiz->title = $this->title;
        $this->quiz->slug = $this->slug;
        $this->quiz->description = $this->description;
        $this->quiz->published = $this->published;
        $this->quiz->public = $this->public;

        $this->quiz->save();

        $this->quiz->questions()->sync($this->questions);

        return to_route('quizzes');
    }

    public function getQuestionsListProperty()
    {
        return Question::pluck('text', 'id')->mapWithKeys(function($text, $id) {
            return [(string) $id => $text];
        })->toArray();
    }
    protected function initListsForFields()
    {
        $this->listsForFields['questions'] = Question::pluck('text', 'id')->mapWithKeys(function($text, $id) {
            return [(string) $id => $text];
        })->toArray();
    }
    // Add this method to ensure the component is properly initialized
    public function hydrate()
    {
        if (empty($this->listsForFields)) {
            $this->initListsForFields();
        }
    }

    public function render(): View
    {
        return view('livewire.quiz.quiz-form');
    }
}
