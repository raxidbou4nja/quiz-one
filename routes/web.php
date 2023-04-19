<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/quiz', [App\Http\Controllers\QuizController::class, 'getRandomQuestion'])->name('quiz');
Route::post('/getQuestion', [App\Http\Controllers\QuizController::class, 'getRandomQuestion'])->name('getQuestion');
Route::post('/checkCorrectAnswer', [App\Http\Controllers\QuizController::class, 'checkCorrectAnswer'])->name('checkCorrectAnswer');
Route::post('/getQuestionById', [App\Http\Controllers\QuizController::class, 'getQuestionById'])->name('getQuestionById');
Route::post('/addQuestion', [App\Http\Controllers\AdminController::class, 'addQuestion'])->name('addQuestion');
Route::post('/editQuestion', [App\Http\Controllers\AdminController::class, 'editQuestion'])->name('editQuestion');
Route::get('/deleteQuestion/{id}', [App\Http\Controllers\AdminController::class, 'deleteQuestion'])->name('deleteQuestion');
Route::get('/add_new_questions', [App\Http\Controllers\AdminController::class, 'addQuestionsFromApi'])->name('addQuestionsFromApi');
