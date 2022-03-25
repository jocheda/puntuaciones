<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Http;
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

Route::get('typicode/posts/title', [PostController::class, 'title'])->name('posts.title');
Route::get('typicode/posts/body', [PostController::class, 'body'])->name('posts.body');
Route::get('typicode/posts/rating', [PostController::class, 'rating'])->name('posts.rating');
Route::get('typicode/posts/saludo', [PostController::class, 'saludo'])->name('posts.saludo');

