<?php

namespace App\Livewire\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuestionForm extends Component
{
    public Question $question;

    // Add these properties for explicit binding
    public string $text = 'The quadratic formula is $$x = \frac{-b \pm \sqrt{b^2 - 4ac}}{2a}$$. Another example: $E=mc^2$';
    public ?string $code_snippet = '';
    public ?string $answer_explanation = '';
    public ?string $more_info_link = '';
    public int $duration = 0; // duración en segundos

    public array $options = [];

    public bool $editing = false;

    protected $rules = [
        'text' => 'required|string',
        'code_snippet' => 'nullable|string',
        'answer_explanation' => 'nullable|string',
        'more_info_link' => 'nullable|url',
        'options' => 'required|array',
        'options.*.text' => 'required|string',
        'duration' => 'required|integer|min:1', // regla para duración
    ];

    public function mount(Question $question): void
    {
        $this->question = $question;

        if ($this->question->exists) {
            $this->editing = true;

            // Fill the properties from the model
            $this->text = $this->question->text;
            $this->code_snippet = $this->question->code_snippet;
            $this->answer_explanation = $this->question->answer_explanation;
            $this->more_info_link = $this->question->more_info_link;
            $this->duration = $this->question->duration; // cargar duración

            foreach ($this->question->options as $option) {
                $this->options[] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'correct' => $option->correct,
                ];
            }
        }
    }

    public function addOption(): void
    {
        $this->options[] = [
            'text' => '',
            'correct' => false
        ];
    }

    public function removeOption(int $index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save()
    {
        
        $this->validate();

        // Update the model with the form data
        $this->question->text = $this->text;
        $this->question->code_snippet = $this->code_snippet;
        $this->question->answer_explanation = $this->answer_explanation;
        $this->question->more_info_link = $this->more_info_link;
        $this->question->duration = $this->duration; // guardar duración
        $this->question->user_id = auth()->user()->id;

        $this->question->save();

        $this->question->options()->delete();

        foreach ($this->options as $option) {
            $this->question->options()->create($option);
        }

        return to_route('questions');
    }

    public function render(): View
    {
        return view('livewire.question.question-form');
    }
}
