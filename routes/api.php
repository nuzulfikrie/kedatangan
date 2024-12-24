<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramWebhookController;
use App\Http\Controllers\Api\SlackWebhookController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/webhooks/telegram', [TelegramWebhookController::class, 'handle']);
Route::post('/webhooks/slack', [SlackWebhookController::class, 'handle']);
Route::get('/oauth/slack/callback', [SlackWebhookController::class, 'handleOAuth'])->name('slack.oauth.callback');
