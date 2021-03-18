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

/* Route::get('/', function () {
    return view('welcome');
}); */

Auth::routes(['register' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('teacher', 'App\Http\Controllers\TeacherController');
Route::resource('term', 'App\Http\Controllers\TermController');
Route::resource('student', 'App\Http\Controllers\StudentController');
Route::resource('studentMark', 'App\Http\Controllers\StudentMarkController');
