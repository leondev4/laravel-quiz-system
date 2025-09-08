<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Subject;

class HomeController extends Controller
{
    public function index()
    {
        $author_id = request('author_id');
        $subject_id = request('subject_id'); // Filtro por materia

        $authors = \App\Models\User::whereHas('quizzes')->paginate(10);
        
        // Obtener materias que tienen quizzes PUBLICADOS
        $subjects = Subject::whereHas('quizzes', function($query) {
            $query->whereHas('questions')
                  ->where('published', 1); // Solo quizzes publicados
        })->active()->orderBy('name')->get();

        $query = Quiz::whereHas('questions')
            ->with(['user', 'subject']) // Cargar relaciones
            ->withCount('questions')
            ->where('published', 1) // SIEMPRE filtrar solo quizzes publicados en home
            ->when($author_id, function ($query) use ($author_id) {
                return $query->where('user_id', $author_id);
            })
            ->when($subject_id, function ($query) use ($subject_id) {
                return $query->where('subject_id', $subject_id);
            })
            ->latest();

        $registered_only_quizzes = $query->get();

        return view('home', compact('registered_only_quizzes', 'authors', 'author_id', 'subject_id', 'subjects'));
    }

    public function show(Quiz $quiz)
    {
        return view('front.quizzes.show', compact('quiz'));
    }
}
