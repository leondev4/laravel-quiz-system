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

        for ($questionIndex = 0; $questionIndex < $this->questionsCount; $questionIndex++) {
            $this->answersOfQuestions[$questionIndex] = [];
        }
    }

    #[Computed]
    public function questionsCount(): int
    {
        return $this->questions->count();
    }

    public function nextQuestion()
    {
        $this->currentQuestionIndex++;

        if ($this->currentQuestionIndex == $this->questionsCount) {
            return $this->submit();
        }

        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
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
            $correctOptionIds = $question->options->where('correct', true)->pluck('id')->toArray();
            
            // Normalize selectedAnswers to always be an array
            if (!is_array($selectedAnswers)) {
                $selectedAnswers = $selectedAnswers ? [$selectedAnswers] : [];
            }
            
            // Remove empty values
            $selectedAnswers = array_filter($selectedAnswers);
            
            // Check if the selected answers match exactly with correct answers
            $isCorrect = !empty($selectedAnswers) && 
                         count(array_diff($correctOptionIds, $selectedAnswers)) === 0 && 
                         count(array_diff($selectedAnswers, $correctOptionIds)) === 0;
            
            if ($isCorrect) {
                $result++;
            }

            // Create answer records for each selected option
            if (!empty($selectedAnswers)) {
                foreach ($selectedAnswers as $optionId) {
                    Answer::create([
                        'user_id' => auth()->id(),
                        'test_id' => $test->id,
                        'question_id' => $question->id,
                        'option_id' => $optionId,
                        'correct' => $isCorrect ? 1 : 0
                    ]);
                }
            } else {
                // No answer selected
                Answer::create([
                    'user_id' => auth()->id(),
                    'test_id' => $test->id,
                    'question_id' => $question->id,
                    'correct' => 0
                ]);
            }
        }

        $test->update([
            'result' => $result
        ]);

        return $this->redirect(route('results.show', ['test' => $test]));
    }

    public function render(): View
    {
        return view('livewire.front.quizzes.show');
    }
}
