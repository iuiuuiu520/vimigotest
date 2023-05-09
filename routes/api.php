<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthenticationController;

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

Route::post('/register',[AuthenticationController::class,'register']);
Route::post('/login',[AuthenticationController::class,'login']);


Route::middleware('auth:api')->prefix('v1')->group(function(){

    Route::post('/logout',[AuthenticationController::class,'logout']);
    
    Route::apiResource('/students',StudentController::class);

    Route::get('/students/search/{searchcond}',[StudentController::class,'findbycond']);

    Route::post('/importstudents',[StudentController::class,'uploadStudents']);

    Route::post('/updatestudents',[StudentController::class,'updateStudents']);

    Route::post('/deletestudents',[StudentController::class,'deleteStudents']);
});



