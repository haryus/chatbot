<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FAQController;
use App\Http\Controllers\API\KnowledgeArticleController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('faqs', FAQController::class);
    Route::apiResource('knowledge-articles', KnowledgeArticleController::class);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/chat', [ChatController::class, 'send']);

    Route::get('/conversations', [ChatController::class, 'conversations']);

    Route::get('/conversation/{id}', [ChatController::class, 'messages']);

    Route::delete('/conversation/{id}', [ChatController::class, 'deleteConversation']);

    // Route::post('/conversation/new', [ChatController::class, 'newConversation']);

});
