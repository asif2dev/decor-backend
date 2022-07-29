<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DesignTypeController;
use App\Http\Controllers\EnvController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalReviewController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectImageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SpaceController;
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

Route::get('scrap', function () {
    dd((new \App\Modules\Scraper\MacknMall())->get());
});

Route::get('sitemap', [SitemapController::class, 'get']);

Route::group(
    ['middleware' => 'auth:sanctum'],
    static function () {
        Route::get('images/{slug}/toggleFavorite', [UserController::class, 'toggleFavoriteProjectImage']);
    }
);


Route::get('categories', [CategoriesController::class, 'getAll']);
Route::get('inspire', [ProjectImageController::class, 'inspire']);
Route::get('project-spaces', [SpaceController::class, 'get']);
Route::get('images/{slug}', [ProjectImageController::class, 'getImagesBySlug']);

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
        Route::post('/toggleFavorite/{professionalUid}', [UserController::class, 'toggleFavoriteProfessional']);
        Route::get('/favorites-professionals', [UserController::class, 'getFavoritesProfessionals']);
        Route::get('/favorites-images', [UserController::class, 'getFavoritesImages']);
    }
);

Route::get('/category', [ProfessionalController::class, 'search']);

Route::get('/professionals/top-rated', [ProfessionalController::class, 'getTopRated']);
Route::get('/professionals/{professionalUid}/projects', [ProjectController::class, 'getProjects']);
Route::get('professionals/{professionalUid}/reviews', [ProfessionalReviewController::class, 'getReviews']);
Route::get('professionals/{professionalSlug}', [ProfessionalController::class, 'get']);

Route::get('app-config', [AppController::class, 'getAppConfig']);

Route::get('/projects/latest', [ProjectController::class, 'getLatestProjects']);
Route::get('/spaces/{spaces}/images', [ProjectImageController::class, 'getImagesBySpace']);
Route::get('/tags', [TagController::class, 'getTags']);
Route::get('/design-types', [DesignTypeController::class, 'getDesignTypes']);
Route::get('/projects/{uid}/similar', [ProjectController::class, 'getSimilar']);
Route::get('/projects/{uid}', [ProjectController::class, 'get']);

Route::post('/professionals', [ProfessionalController::class, 'store']);

Route::group(
    ['prefix' => 'professionals', 'middleware' => 'auth:sanctum'],
    static function () {
        Route::post('/{professionalUid}/projects/{id}/update', [ProjectController::class, 'update']);
        Route::delete('/{professionalUid}/projects/{id}/delete', [ProjectController::class, 'delete']);
        Route::post('/{professionalUid}/projects/{id}/update-images', [ProjectImageController::class, 'updateImages']);
        Route::post('/{professionalUid}/projects', [ProjectController::class, 'store']);
        Route::post('/{professionalUid}/reviews', [ProfessionalReviewController::class, 'writeReview']);
        Route::post('/{professionalUid}', [ProfessionalController::class, 'update']);
    }
);
