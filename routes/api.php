<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/home', 'show');
        Route::put('/home', 'update')
            ->middleware('auth:sanctum');
    });

Route::controller(NewsController::class)
    ->group(function () {
        Route::get('/news', 'index');

        Route::post('/news', 'store')
            ->middleware('auth:sanctum');
        Route::put('/news/{id}', 'update')
            ->middleware('auth:sanctum');
        Route::delete('/news/{id}', 'destroy')
            ->middleware('auth:sanctum');
    });

Route::controller(AboutController::class)
    ->group(function () {
        Route::get('/about-me', 'show');
        Route::put('/about-me', 'update');
    });

Route::controller(CalendarController::class)
    ->group(function () {
        Route::get('/calendar', 'index');
    });

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login');
        Route::get('/verify-token', function () {
            return ['authorized' => true];
        })
            ->middleware('auth:sanctum');
    });

Route::controller(AdminController::class)
    ->prefix('/admin')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('dashboard', 'dashboard');
        Route::post('home/image', 'handleUploadedImage');
        Route::post('about/image', 'handleUploadedImage');
    });
