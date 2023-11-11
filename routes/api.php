<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\StudentController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('students', StudentController::class);
    Route::post('students/search', [StudentController::class, 'search']);
    Route::post('students/import', [StudentController::class, 'import']);
});

