<?php

namespace App\Livewire\Question;

use App\Models\Question;
use App\Models\Subject;
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
    public int $duration = 0;
    public ?int $subject_id = null;

    public array $options = [];

    public bool $editing = false;

    protected $rules = [
        'text' => 'required|string',
        'code_snippet' => 'nullable|string',
        'answer_explanation' => 'nullable|string',
        'more_info_link' => 'nullable|url',
        'options' => 'required|array|min:2',
        'options.*.text' => 'required|string',
        'duration' => 'required|integer|min:1',
        'subject_id' => 'required|exists:subjects,id',
    ];

    // Mensajes de validación personalizados
    protected $messages = [
        'subject_id.required' => 'La materia es obligatoria.',
        'subject_id.exists' => 'La materia seleccionada no existe.',
        'duration.required' => 'La duración es obligatoria.',
        'duration.min' => 'La duración debe ser al menos 1 segundo.',
        'options.min' => 'Debe tener al menos 2 opciones de respuesta.',
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
            $this->duration = $this->question->duration;
            $this->subject_id = $this->question->subject_id;

            foreach ($this->question->options as $option) {
                $this->options[] = [
                    'id' => $option->id,
                    'text' => $option->text,
                    'correct' => $option->correct,
                ];
            }
        }
        
        // Asegurar que siempre haya al menos 2 opciones para empezar
        if (count($this->options) < 2) {
            while (count($this->options) < 2) {
                $this->addOption();
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
        // Solo permitir eliminar si quedan más de 2 opciones
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function save()
    {
        $this->validate();

        // Verificar que al menos una opción sea correcta
        $hasCorrectAnswer = collect($this->options)->contains('correct', true);
        if (!$hasCorrectAnswer) {
            $this->addError('options', 'Debe marcar al menos una opción como correcta.');
            return;
        }

        // Update the model with the form data
        $this->question->text = $this->text;
        $this->question->code_snippet = $this->code_snippet;
        $this->question->answer_explanation = $this->answer_explanation;
        $this->question->more_info_link = $this->more_info_link;
        $this->question->duration = $this->duration;
        $this->question->subject_id = $this->subject_id;
        $this->question->user_id = auth()->user()->id;

        $this->question->save();

        $this->question->options()->delete();

        foreach ($this->options as $option) {
            $this->question->options()->create($option);
        }

        return to_route('questions');
    }

    // Método para obtener las materias disponibles
    public function getSubjectsProperty()
    {
        return Subject::active()->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.question.question-form');
    }
}
