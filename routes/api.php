<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Enum\UserRole;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post(
    '/login',
    ['as' => 'login', 'uses' => \App\Component\Auth\LoginController::class  . '@authenticate']
);

Route::post(
    '/register',
    ['as' => 'register', 'uses' => \App\Component\Register\RegisterController::class  . '@create']
);

Route::middleware('check_user_role:' . UserRole::ROLE_ADMIN)
    ->post(
    '/import-books',
    ['as' => 'import-books', 'uses' => \App\Component\ImportBooks\ImportBooksController::class  . '@import']
);

Route::get(
    '/books',
    ['as' => 'books', 'uses' => \App\Component\SearchBooks\BooksController::class  . '@list']
);
