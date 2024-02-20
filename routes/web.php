<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/auth-vpn', [App\Http\Controllers\VPNController::class, 'auth']);
Route::get('/client/all', [App\Http\Controllers\VPNController::class, 'getClients']);
Route::get('/client/create/{name}', [App\Http\Controllers\VPNController::class, 'createClient']);
Route::get('/client/qr/{name}', [App\Http\Controllers\VPNController::class, 'getQrCode']);
Route::get('/client/file/{name}', [App\Http\Controllers\VPNController::class, 'getFile']);
Route::get('/client/disable/{name}', [App\Http\Controllers\VPNController::class, 'disableClient']);
Route::get('/client/enable/{name}', [App\Http\Controllers\VPNController::class, 'enableClient']);
Route::get('/client/delete/{name}', [App\Http\Controllers\VPNController::class, 'deleteClient']);
