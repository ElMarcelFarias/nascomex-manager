<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\HarborController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ShippingInstructionController;
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

//Rotas API numerário
Route::get('shipping-instruction', [ShippingInstructionController::class, 'index']);
Route::get('shipping-instruction/{id}', [ShippingInstructionController::class, 'show']);
Route::post('shipping-instruction', [ShippingInstructionController::class, 'create']);
Route::put('shipping-instruction/{id}', [ShippingInstructionController::class, 'update']);
Route::delete('shipping-instruction/{id}', [ShippingInstructionController::class, 'destroy']);


