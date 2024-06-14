<?php

use App\Http\Controllers\BankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('bank', [BankController::class, 'index']);
Route::get('bank/{id}', [BankController::class, 'show']);
Route::post('bank', [BankController::class, 'create']);
Route::put('bank/{id}', [BankController::class, 'update']);
Route::delete('bank/{id}', [BankController::class, 'destroy']);





//usar como referência

// Route::post('/users/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
// Route::get('/users/{id}/comments/create', [CommentController::class, 'create'])->name('comments.create');
// Route::get('/users/{id}/comments', [CommentController::class, 'index'])->name('comments.index');
// Route::get('/users/{id}/comments/{id_comments}/edit', [CommentController::class, 'edit'])->name('comments.edit');
// Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update');

// //editar parcialmente um registro patch, editar todo o registro put
// Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
// Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');   
// Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');   
// Route::get('/users', [UserController::class, 'index'])->name('users.index');   
// Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
// Route::post('/users', [UserController::class, 'store'])->name('users.store');
// Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');   

