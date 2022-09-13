<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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

Route::get('/student/get', [MainController::class, 'getstudent']);
Route::post('/student/store', [MainController::class, 'storestudent'])->name('studentadd');
Route::get('/student/edit/{id}', [MainController::class, 'editstudent']);
Route::post('/student/update/{id}', [MainController::class, 'updatedata']);
Route::get('/student/delete/{id}', [MainController::class, 'deletestudent']);
