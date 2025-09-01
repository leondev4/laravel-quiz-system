<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class HomeController extends Controller
{
    public function index()
    {
         $author_id = request('author_id');

    $authors = \App\Models\User::whereHas('quizzes')->paginate(10);

    $query = Quiz::whereHas('questions')
        ->withCount('questions')
        ->when(auth()->guest() || !auth()->user()->is_admin, function ($query) {
            return $query->where('published', 1);
        })
        ->when($author_id, function ($query) use ($author_id) {
            return $query->where('user_id', $author_id);
        })
        ->select(['id', 'title', 'slug', 'public', 'opens_at', 'closes_at', 'user_id']) // Agregar campos de fecha
        ->get();

    $public_quizzes = $query->where('public', 1);
    $registered_only_quizzes = $query->where('public', 0);

    return view('home', compact('public_quizzes', 'registered_only_quizzes', 'authors', 'author_id'));
    }

    public function show(Quiz $quiz)
    {
        return view('front.quizzes.show', compact('quiz'));
    }
}
