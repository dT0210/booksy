<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function() {
    if (Auth::check()) {
        return app(BookController::class)->index();
    } else {
        return view('welcome');
    }
});

Route::get('/book/show/{book}', [BookController::class, 'show']);

Route::get('/shelves', function() {
    if (Auth::check()) {
        return app(UserController::class)->booksInShelves(Auth::user());
    } else {
        return view('welcome');
    }
});

Route::get('/author', [AuthorController::class, 'index']);

Route::get('/author/show/{author}', [AuthorController::class, 'show']);

Route::get('/book/create', [BookController::class, 'create']);

Route::get('/user/sign_up', [UserController::class, 'create']);

Route::get('/user/sign_in', [UserController::class, 'sign_in']);

Route::post('/user/sign_up_handle', [UserController::class, 'store']);

Route::post('/user/authenticate', [UserController::class, 'authenticate']);

Route::post('/user/sign_out', [UserController::class, 'sign_out']);

Route::post('/book/shelf', [BookController::class, 'shelf']);

Route::post('/book/rate', [BookController::class, 'rate']);

Route::get('/search', [BookController::class, 'search']);

Route::get('/recommendations', function() {return view('wip');});

Route::get('/user/profile', function() {return view('wip');});

Route::get('/user/settings', function() {return view('wip');});

Route::get('/genre/show/{genre}', [BookController::class, 'genre']);

Route::get('/.well-known/pki-validation/20AB0D5BA06CC241A7CD847357014D28.txt', function () {
    return response()->file(public_path('.well-known/pki-validation/20AB0D5BA06CC241A7CD847357014D28.txt'));
});