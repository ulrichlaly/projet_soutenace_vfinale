<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\PaieController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\CongeController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Routes d'authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']); // ✅ CORRECTION ICI
    
    // Routes générales
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/collaborators', [CollaboratorController::class, 'index']);
    Route::put('/profile', [CollaboratorController::class, 'updateProfile']);
    Route::get('/collaborators/{id}/conges', [CongeController::class, 'historique']);
    Route::get('/collaborators/{id}/paies', [PaieController::class, 'historique']);
    
    // Gestion des congés (tous les utilisateurs authentifiés)
    Route::apiResource('conges', CongeController::class);
    
    // Routes Admin uniquement
    Route::middleware('role:Admin,Administrateur,Admin Général')->group(function () {
        Route::post('/collaborators', [CollaboratorController::class, 'store']);
        Route::get('/collaborators/{id}', [CollaboratorController::class, 'show']);
        Route::put('/collaborators/{id}', [CollaboratorController::class, 'update']);
        Route::delete('/collaborators/{id}', [CollaboratorController::class, 'destroy']);
        Route::get('/users', [UserController::class, 'index']);
    });
    
    // Routes pour Admin et Comptable
    Route::middleware('role:Admin,Administrateur,Admin Général,Comptable')->group(function () {
        // Gestion des paies
        Route::apiResource('paies', PaieController::class);
        Route::post('/paies/generate-auto', [PaieController::class, 'generateAuto']);
        
        // Gestion des déclarations
        Route::apiResource('declarations', DeclarationController::class);
        Route::post('/declarations/generate-auto', [DeclarationController::class, 'generateAuto']);
    });
});
