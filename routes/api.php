<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalReviewController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
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

Route::group(
    ['prefix' => 'auth'],
    static function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('loginFB', [LoginController::class, 'loginFB']);
    }
);

Route::group(
    ['prefix' => 'user', 'middleware' => 'auth:sanctum'],
    static function () {
        Route::get('/me', [UserController::class, 'getLoggedInUser']);
    }
);

Route::get('/professionals/top-rated', [ProfessionalController::class, 'getTopRated']);
Route::get('/professionals/{professionalUid}/projects', [ProjectController::class, 'getProjects']);
Route::get('professionals/{professionalUid}/reviews', [ProfessionalReviewController::class, 'getReviews']);
Route::group(
    ['prefix' => 'professionals', 'middleware' => 'auth:sanctum'],
    static function () {
        Route::post('/{professionalUid}/projects', [ProjectController::class, 'store']);
        Route::post('/{professionalUid}/reviews', [ProfessionalReviewController::class, 'writeReview']);
        Route::post('/', [ProfessionalController::class, 'store']);
    }
);
