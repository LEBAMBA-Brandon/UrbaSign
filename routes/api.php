<?php

use App\Http\Controllers\Api\ArrondissementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommuneController;
use App\Http\Controllers\Api\QuartierController;
use App\Http\Controllers\Api\SignalementController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// 1) ROUTES PUBLIQUES : pas besoin d'être connecté

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/signalements', [SignalementController::class, 'index']);
Route::get('/signalements/{signalement}', [SignalementController::class, 'show'])
    ->whereNumber('signalement');

Route::apiResource('communes', CommuneController::class)->only(['index', 'show']);
Route::apiResource('arrondissements', ArrondissementController::class)->only(['index', 'show']);
Route::apiResource('quartiers', QuartierController::class)->only(['index', 'show']);

// 2) ROUTES CONNECTÉES : il faut envoyer le token

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::post('/signalements', [SignalementController::class, 'store']);
    Route::get('/signalements/mes-signalements', [SignalementController::class, 'mesSignalements']);

    // 3) ROUTES ADMIN : connecté ET rôle admin

    Route::middleware('can:admin')->group(function () {
        Route::patch('/signalements/{signalement}/statut', [SignalementController::class, 'updateStatut']);
        Route::delete('/signalements/{signalement}', [SignalementController::class, 'destroy']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('communes', CommuneController::class)->except(['index', 'show']);
        Route::apiResource('arrondissements', ArrondissementController::class)->except(['index', 'show']);
        Route::apiResource('quartiers', QuartierController::class)->except(['index', 'show']);
    });
});