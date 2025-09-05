<?php

namespace App\Livewire\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
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
    public ?string $opens_at = '';
    public ?string $closes_at = '';
    public ?int $subject_id = null;

    public array $questions = [];

    public bool $editing = false;

    public array $listsForFields = [];

    protected $rules = [
        'title' => 'required|string',
        'slug' => 'string',
        'description' => 'nullable|string',
        'published' => 'boolean',
        'public' => 'boolean',
        'opens_at' => 'nullable|date',
        'closes_at' => 'nullable|date|after:opens_at',
        'subject_id' => 'required|exists:subjects,id',
        'questions' => 'nullable|array',
    ];

    // Mensajes de validación personalizados
    protected $messages = [
        'subject_id.required' => 'La materia es obligatoria.',
        'subject_id.exists' => 'La materia seleccionada no existe.',
    ];

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;

        if ($this->quiz->exists) {
            $this->editing = true;
            
            // Fill the properties from the model
            $this->title = $this->quiz->title;
            $this->slug = $this->quiz->slug;
            $this->description = $this->quiz->description;
            $this->published = $this->quiz->published;
            $this->public = $this->quiz->public; // Corregir esta línea
            $this->subject_id = $this->quiz->subject_id;
            $this->opens_at = $this->quiz->opens_at?->format('Y-m-d\TH:i') ?? '';
            $this->closes_at = $this->quiz->closes_at?->format('Y-m-d\TH:i') ?? '';
            
            $this->questions = $this->quiz->questions->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }

        $this->initListsForFields();
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    public function save()
    {
        $this->validate();

        $this->quiz->title = $this->title;
        $this->quiz->slug = $this->slug;
        $this->quiz->description = $this->description;
        $this->quiz->published = $this->published;
        $this->quiz->public = $this->public; // Corregir esta línea - era $this->quiz->public
        $this->quiz->subject_id = $this->subject_id;
        $this->quiz->opens_at = $this->opens_at ? \Carbon\Carbon::parse($this->opens_at) : null;
        $this->quiz->closes_at = $this->closes_at ? \Carbon\Carbon::parse($this->closes_at) : null;
        
        // Guardar el id del usuario actual
        $this->quiz->user_id = auth()->id();

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
        
        // Solo materias activas
        $this->listsForFields['subjects'] = Subject::active()->pluck('name', 'id')->toArray();
    }
    
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
