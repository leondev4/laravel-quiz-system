<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Livewire\Admin\AdminForm;
use App\Livewire\Admin\AdminList;
use App\Livewire\Admin\Tests\TestList;
use App\Livewire\Front\Leaderboard;
use App\Livewire\Front\Results\ResultList;
use App\Livewire\Question\QuestionForm;
use App\Livewire\Question\QuestionList;
use App\Livewire\Quiz\QuizForm;
use App\Livewire\Quiz\QuizList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('throttle:1,1')->group(function () {
    //vistar quiz para responder preguntas
    Route::get('quiz/{quiz}', [HomeController::class, 'show'])->name('quiz.show');
});
//mostrar resultados despues de terminar el quiz
Route::get('results/{test}', [ResultController::class, 'show'])->name('results.show');

// protected routes
Route::middleware('auth')->group(function () {
    Route::get('leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('myresults', ResultList::class)->name('myresults');

    // Admin routes
    Route::middleware('isAdmin')->group(function () {
        Route::get('questions', QuestionList::class)->name('questions');
        Route::get('questions/create', QuestionForm::class)->name('question.create');
        Route::get('questions/{question}', QuestionForm::class)->name('question.edit');

        Route::get('quizzes', QuizList::class)->name('quizzes');
        Route::get('quizzes/create', QuizForm::class)->name('quiz.create');
        Route::get('quizzes/{quiz}/edit', QuizForm::class)->name('quiz.edit');

        Route::get('admins', AdminList::class)->name('admins');
        Route::get('admins/create', AdminForm::class)->name('admin.create');

        Route::get('tests', TestList::class)->name('tests');
    });
});


require __DIR__ . '/auth.php';
