<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FAQController;
use App\Http\Controllers\API\KnowledgeArticleController;
use App\Http\Controllers\API\LiveSupportController;
use App\Http\Controllers\API\AiProviderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('faqs', FAQController::class);
    Route::apiResource('knowledge-articles', KnowledgeArticleController::class);

    // Live Support — agent actions
    Route::get('/live-support/sessions',        [LiveSupportController::class, 'sessions']);
    Route::post('/live-support/{id}/join',      [LiveSupportController::class, 'join']);

    // AI Provider management
    Route::get('/ai-providers',                 [AiProviderController::class, 'index']);
    Route::post('/ai-providers',                [AiProviderController::class, 'store']);
    Route::put('/ai-providers/{id}',            [AiProviderController::class, 'update']);
    Route::delete('/ai-providers/{id}',         [AiProviderController::class, 'destroy']);
    Route::post('/ai-providers/{id}/activate',  [AiProviderController::class, 'activate']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/get-faqs', [ChatController::class, 'getFAQs']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/chat', [ChatController::class, 'send']);

    Route::get('/conversations', [ChatController::class, 'conversations']);

    Route::get('/conversation/{id}', [ChatController::class, 'messages']);

    Route::delete('/conversation/{id}', [ChatController::class, 'deleteConversation']);

    // Live Support
    Route::post('/live-support/start',              [LiveSupportController::class, 'start']);
    Route::get('/live-support/my-sessions',         [LiveSupportController::class, 'mySessions']);
    Route::get('/live-support/{id}/messages',       [LiveSupportController::class, 'messages']);
    Route::post('/live-support/{id}/send',          [LiveSupportController::class, 'sendMessage']);
    Route::post('/live-support/{id}/close',         [LiveSupportController::class, 'close']);

    // // Route::post('/conversation/new', [ChatController::class, 'newConversation']);

});
