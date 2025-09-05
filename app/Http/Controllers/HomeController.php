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
        
        // Obtener materias que tienen quizzes
        $subjects = Subject::whereHas('quizzes', function($query) {
            $query->whereHas('questions')
                  ->when(auth()->guest() || !auth()->user()->is_admin, function ($q) {
                      return $q->where('published', 1);
                  });
        })->active()->orderBy('name')->get();

        $query = Quiz::whereHas('questions')
            ->with(['user', 'subject']) // Cargar relaciones
            ->withCount('questions')
            ->when(auth()->guest() || !auth()->user()->is_admin, function ($query) {
                return $query->where('published', 1);
            })
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
