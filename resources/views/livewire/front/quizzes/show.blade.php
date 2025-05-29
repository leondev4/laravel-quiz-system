<div x-data="{ secondsLeft: {{ config('quiz.secondsPerQuestion') }} }" x-init="setInterval(() => {
    if (secondsLeft > 1) { secondsLeft--; } else {
        secondsLeft = {{ config('quiz.secondsPerQuestion') }};
        $wire.nextQuestion();
    }
}, 1000);">

    <div class="mb-2">
        Time left for this question: <span x-text="secondsLeft" class="font-bold"></span> sec.
    </div>

    <span class="text-bold">Question {{ $currentQuestionIndex + 1 }} of {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-2xl">{{ $currentQuestion->text }}</h2>

    @if ($currentQuestion->code_snippet)
        <pre class="mb-4 border-2 border-solid bg-gray-50 p-2">{{ $currentQuestion->code_snippet }}</pre>
    @endif

    @php
        $correctAnswersCount = $currentQuestion->options->where('correct', true)->count();
        $isMultipleChoice = $correctAnswersCount > 1;
    @endphp

    <div class="mb-4">
        @if ($isMultipleChoice)
            <p class="text-sm text-gray-600 mb-2">Select all correct answers:</p>
        @else
            <p class="text-sm text-gray-600 mb-2">Select the correct answer:</p>
        @endif
        
        @foreach ($currentQuestion->options as $option)
            <div class="mb-2">
                <label for="option.{{ $option->id }}" class="flex items-center cursor-pointer">
                    @if ($isMultipleChoice)
                        <input type="checkbox" 
                               id="option.{{ $option->id }}"
                               wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                               value="{{ $option->id }}"
                               class="mr-2">
                    @else
                        <input type="radio" 
                               id="option.{{ $option->id }}"
                               wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                               value="{{ $option->id }}"
                               class="mr-2">
                    @endif
                    <span>{{ $option->text }}</span>
                </label>
            </div>
        @endforeach
    </div>

    @if ($currentQuestionIndex < $this->questionsCount - 1)
        <div class="mt-4">
            <x-secondary-button
                x-on:click="secondsLeft = {{ config('quiz.secondsPerQuestion') }}; $wire.nextQuestion();">
                Next question
            </x-secondary-button>
        </div>
    @else
        <div class="mt-4">
            <x-primary-button x-on:click="$wire.submit();">Submit
            </x-primary-button>
        </div>
    @endif
</div>
