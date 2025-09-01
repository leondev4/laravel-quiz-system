<?php

namespace App\Livewire\Front\Quizzes;

use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Show extends Component
{
    public Quiz $quiz;

    public Collection $questions;

    public Question $currentQuestion;
    public int $currentQuestionIndex = 0;

    public array $answersOfQuestions = [];

    public int $startTimeInSeconds = 0;

    public function mount()
    {
        $this->startTimeInSeconds = now()->timestamp;

        $this->questions = Question::query()
            ->inRandomOrder()
            ->whereRelation('quizzes', 'id', $this->quiz->id)
            ->with('options')
            ->get();

        ray($this->questions);

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];

        // Initialize answers array for each question
        for ($questionIndex = 0; $questionIndex < $this->questionsCount; $questionIndex++) {
            $this->answersOfQuestions[$questionIndex] = [];
        }
    }

    #[Computed]
    public function questionsCount(): int
    {
        return $this->questions->count();
    }

    // Método para obtener la duración de la pregunta actual
    public function getCurrentQuestionDuration()
    {
        return $this->currentQuestion->duration ?? config('quiz.secondsPerQuestion');
    }

    public function nextQuestion()
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex == $this->questionsCount) {
            return $this->submit();
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        
        // Disparar evento para actualizar el timer con la nueva duración
        $this->dispatch('question-changed', duration: $this->getCurrentQuestionDuration());
    }

    public function submit()
    {
        $result = 0;

        $test = Test::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'result' => 0,
            'ip_address' => request()->ip(),
            'time_spent' => now()->timestamp - $this->startTimeInSeconds
        ]);

        foreach ($this->answersOfQuestions as $key => $selectedAnswers) {
            $question = $this->questions[$key];

            // Handle multiple selection answers
            if (is_array($selectedAnswers) && !empty($selectedAnswers)) {
                foreach ($selectedAnswers as $selectedAnswer) {
                    $selectedOption = $question->options->where('id', $selectedAnswer)->first();
                    
                    Answer::create([
                        'user_id' => auth()->id(),
                        'test_id' => $test->id,
                        'question_id' => $question->id,
                        'option_id' => $selectedOption->id,
                        'correct' => $selectedOption->correct
                    ]);
                    
                    if ($selectedOption->correct) {
                        $result++;
                    }
                }
            } 
            // Handle single selection answers
            elseif (!is_array($selectedAnswers) && !empty($selectedAnswers)) {
                $selectedOption = $question->options->where('id', $selectedAnswers)->first();
                
                Answer::create([
                    'user_id' => auth()->id(),
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'option_id' => $selectedOption->id,
                    'correct' => $selectedOption->correct
                ]);
                
                if ($selectedOption->correct) {
                    $result++;
                }
            }
            // Handle unanswered questions
            else {
                Answer::create([
                    'user_id' => auth()->id(),
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'option_id' => null,
                    'correct' => false
                ]);
            }
        }

        $test->update(['result' => $result]);

        // Cambiar la redirección para usar la ruta correcta
        return to_route('results.show', $test);
    }

    public function render(): View
    {
        return view('livewire.front.quizzes.show');
    }
}
