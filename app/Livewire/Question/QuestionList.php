<?php

namespace App\Livewire\Question;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class QuestionList extends Component
{
    public function delete(Question $question)
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN, 403);

        $user = auth()->user();

        // Solo permitir eliminar si la pregunta pertenece al usuario actual
        if ($question->user_id !== $user?->id) {
            abort(403, 'No autorizado');
        }
        $question->delete();
    }

    public function render(): View
    {
        // $questions = Question::latest()->paginate();
        $user = auth()->user();
        $questions = Question::where('user_id', $user?->id)
            ->latest()
            ->paginate();

        return view('livewire.question.question-list', compact('questions'));
    }
}
