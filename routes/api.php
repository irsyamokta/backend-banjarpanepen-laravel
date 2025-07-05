<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SettingController;

Route::prefix('v1')->group(function () {
    Route::get('/', function (Request $request) {
        return 'Running';
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me'])->middleware('CheckAuth');
        Route::get('/verify/{token}', [AuthController::class, 'verifyEmail']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/all', [UserController::class, 'getUsers']);
        Route::get('/contact', [UserController::class, 'getUserByContact']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::patch('/update', [UserController::class, 'updateUser']);
            Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
        });
    });

    Route::prefix('tour')->group(function () {
        Route::get('/all', [TourController::class, 'getTours']);
        Route::get('/{id}', [TourController::class, 'getTourById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [TourController::class, 'createTour']);
            Route::patch('/update/{id}', [TourController::class, 'updateTour']);
            Route::delete('/delete/{id}', [TourController::class, 'deleteTour']);
        });
    });

    Route::prefix('event')->group(function () {
        Route::get('/all', [EventController::class, 'getEvents']);
        Route::get('/{id}', [EventController::class, 'getEventById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [EventController::class, 'createEvent']);
            Route::patch('/update/{id}', [EventController::class, 'updateEvent']);
            Route::delete('/delete/{id}', [EventController::class, 'deleteEvent']);
        });
    });

    Route::prefix('package')->group(function () {
        Route::get('/all', [PackageController::class, 'getPackages']);
        Route::get('/{id}', [PackageController::class, 'getPackageById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [PackageController::class, 'createPackage']);
            Route::patch('/update/{id}', [PackageController::class, 'updatePackage']);
            Route::delete('/delete/{id}', [PackageController::class, 'deletePackage']);
        });
    });

    Route::prefix('article')->group(function () {
        Route::get('/all', [ArticleController::class, 'getArticles']);
        Route::get('/{id}', [ArticleController::class, 'getArticleById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [ArticleController::class, 'createArticle']);
            Route::patch('/update/{id}', [ArticleController::class, 'updateArticle']);
            Route::delete('/delete/{id}', [ArticleController::class, 'deleteArticle']);
        });
    });

    Route::prefix('gallery')->group(function () {
        Route::get('/all', [GalleryController::class, 'getGalleries']);
        Route::get('/{id}', [GalleryController::class, 'getGalleryById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [GalleryController::class, 'createGallery']);
            Route::patch('/update/{id}', [GalleryController::class, 'updateGallery']);
            Route::delete('/delete/{id}', [GalleryController::class, 'deleteGallery']);
        });
    });

    Route::prefix('setting')->group(function () {
        Route::get('/all', [SettingController::class, 'getSettings']);
        Route::get('/{id}', [SettingController::class, 'getSettingById']);
        Route::middleware('CheckAuth:ADMIN')->group(function () {
            Route::post('/create', [SettingController::class, 'createSetting']);
            Route::patch('/update/{id}', [SettingController::class, 'updateSetting']);
            Route::delete('/delete/{id}', [SettingController::class, 'deleteSetting']);
        });
    });
});
