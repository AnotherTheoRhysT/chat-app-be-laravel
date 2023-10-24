<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\GroupMember;
use App\Models\User;

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

Route::get('/users', [UserController::class, 'getList']);
Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('chat')->group(function() {
        Route::prefix('message')->group(function() {
            Route::get('/{id}', [ChatMessageController::class, 'get']);
            Route::post('/send', [ChatMessageController::class, 'send']);
        });

        Route::prefix('conversation')->group(function() {
            Route::get('/', [ConversationController::class, 'get']);
            Route::post('/listen', [ConversationController::class, 'listen']);
            Route::post('/new', [ConversationController::class, 'createConversation']);
        });
    });
});

Route::get('/test/{conversation_id}', function(Request $req, int $conversation_id) {
    // return response()->json([
    //     'user' => $req->user(),
    //     'convo_id' => $conversation_id
    // ]);
    $q = GroupMember::where('conversation_id', $conversation_id)
        ->where('user_id', $req->user()->id)
        ->first();
    // select('id')->
    dd($q !== null);
})->middleware(['auth:sanctum']);