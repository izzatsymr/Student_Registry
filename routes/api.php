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

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://127.0.0.1:8000/outh/token',[
        'form_params' => [
            'grant_type'=> 'password',
            'client_id'=>2,
            'client_secret'=> 'cwu3Q3aBwxjtQONwPP0Zbz1XzwKsPDfZuHJ0xvg1',
            'username'=> 'test@user.com',
            'password'=> 'user',
            'scope'=>''
        ]
    ]);

    return json_decode((string) $response->getBody(),true);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('students', StudentController::class);
    Route::post('students/search', [StudentController::class, 'search']);
    Route::post('students/import', [StudentController::class, 'import']);
});

