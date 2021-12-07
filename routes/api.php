<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\EnvController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalReviewController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
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

Route::get('/', function () {
    return ['apiVersion' => '1.0.0', 'isLogged' => request()->user() !== null];
});

Route::get('env', [EnvController::class, 'get']);

Route::get('categories', [CategoriesController::class, 'getAll']);

Route::group(
    ['prefix' => 'auth'],
    static function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('verify', [LoginController::class, 'verify']);
    }
);

Route::group(
    ['prefix' => 'user', 'middleware' => 'auth:sanctum'],
    static function () {
        Route::post('/', [UserController::class, 'updateUser']);
        Route::get('/me', [UserController::class, 'getLoggedInUser']);
        Route::get('/logout', [UserController::class, 'logout']);
    }
);

Route::get('/category', [ProfessionalController::class, 'search']);

Route::get('/professionals/top-rated', [ProfessionalController::class, 'getTopRated']);
Route::get('/professionals/{professionalUid}/projects', [ProjectController::class, 'getProjects']);
Route::get('professionals/{professionalUid}/reviews', [ProfessionalReviewController::class, 'getReviews']);
Route::get('professionals/{professionalUid}', [ProfessionalController::class, 'get']);

Route::get('/projects/latest', [ProjectController::class, 'getLatestProjects']);
Route::get('/tags', [TagController::class, 'getTags']);
Route::get('/projects/{uid}/similar', [ProjectController::class, 'getSimilar']);
Route::get('/projects/{uid}', [ProjectController::class, 'get']);

Route::group(
    ['prefix' => 'professionals', 'middleware' => 'auth:sanctum'],
    static function () {
        Route::post('/{professionalUid}/projects', [ProjectController::class, 'store']);
        Route::post('/{professionalUid}/reviews', [ProfessionalReviewController::class, 'writeReview']);
        Route::post('/{professionalUid}', [ProfessionalController::class, 'update']);
        Route::post('/', [ProfessionalController::class, 'store']);
    }
);
