<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\HarborController;
use App\Http\Controllers\ImportController;
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

//Rotas API banco
Route::get('bank', [BankController::class, 'index']);
Route::get('bank/{id}', [BankController::class, 'show']);
Route::post('bank', [BankController::class, 'create']);
Route::put('bank/{id}', [BankController::class, 'update']);
Route::delete('bank/{id}', [BankController::class, 'destroy']);


//Rotas API porto
Route::get('harbor', [HarborController::class, 'index']);
Route::get('harbor/{id}', [HarborController::class, 'show']);
Route::post('harbor', [HarborController::class, 'create']);
Route::put('harbor/{id}', [HarborController::class, 'update']);
Route::delete('harbor/{id}', [HarborController::class, 'destroy']);

//Rotas API importador
Route::get('import', [ImportController::class, 'index']);
Route::get('import/{id}', [ImportController::class, 'show']);
Route::post('import', [ImportController::class, 'create']);
Route::put('import/{id}', [ImportController::class, 'update']);
Route::delete('import/{id}', [ImportController::class, 'destroy']);




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

