<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\VisitorSessionController;
use App\Http\Controllers\Api\VisitorProfileController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminConversationController;
use App\Http\Controllers\Api\AdminMessageController;
use Illuminate\Support\Facades\Auth;

/**
 * Broadcasting auth (Visitor)
 */
Route::post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('visitor.session');

Route::post('/broadcasting/auth-admin', function (Request $request) {
    Auth::shouldUse('sanctum'); // مهم
    return Broadcast::auth($request);
})->middleware('auth:sanctum');



// Visitor
Route::prefix('visitor')->group(function () {
    Route::post('/session', [VisitorSessionController::class, 'store']);
    Route::post('/phone', [\App\Http\Controllers\Api\VisitorPhoneController::class, 'store'])
        ->middleware('visitor.session');
});

// Conversations (Visitor)
// نکته: اینها رو با session_token محافظت می‌کنیم و با phone gate
Route::middleware(['visitor.session', 'visitor.phone'])->group(function () {
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->middleware('throttle:chat');
    Route::delete('/messages/{message}/delete', [MessageController::class, 'delete']);
});

// Admin/Auth
Route::prefix('admin')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [\App\Http\Controllers\Api\AdminAuthController::class, 'me']);
        Route::post('/logout', [\App\Http\Controllers\Api\AdminAuthController::class, 'logout']);

        Route::get('/conversations', [AdminConversationController::class, 'index']);
        Route::post('/conversations/{conversation}/assign', [AdminConversationController::class, 'assign']);
        Route::post('/conversations/{conversation}/resolve', [AdminConversationController::class, 'resolve']);
        Route::post('/conversations/{conversation}/close', [AdminConversationController::class, 'close']);
        Route::get('/conversations/{conversation}/messages', [AdminMessageController::class, 'index']);
        Route::post('/conversations/{conversation}/messages', [AdminMessageController::class, 'store']);
    });
});




// Route::middleware('auth:sanctum')->get('/admin/me', function (\Illuminate\Http\Request $r) {
//     return response()->json([
//         'id' => $r->user()->id,
//         'email' => $r->user()->email,
//     ]);
// });  test aadmin









// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\VisitorSessionController;
// use App\Http\Controllers\Api\ConversationController;
// use App\Http\Controllers\Api\MessageController;
// use App\Http\Controllers\Api\AdminConversationController;
// use Illuminate\Support\Facades\Broadcast;
// use Illuminate\Http\Request;



// Route::post('/broadcasting/auth', function (Request $request) {
//     return Broadcast::auth($request);
// })->middleware('visitor.session');

// Route::post('/broadcasting/auth-admin', function (Request $request) {
//     return Broadcast::auth($request);
// })->middleware('admin.secret');



// Route::prefix('visitor')->group(function () {
//     Route::post('/session', [VisitorSessionController::class, 'store']);
// });

// Route::post('/conversations', [ConversationController::class, 'store']);
// Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
// Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
// // Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);


// Route::middleware('throttle:chat')->group(function () {
//     Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
// });



// Route::prefix('admin')->group(function () {
//     Route::get('/conversations', [AdminConversationController::class, 'index']);
//     Route::post('/conversations/{conversation}/assign', [AdminConversationController::class, 'assign']);
//     Route::post('/conversations/{conversation}/resolve', [AdminConversationController::class, 'resolve']);
//     Route::post('/conversations/{conversation}/close', [AdminConversationController::class, 'close']);
//     Route::post('/conversations/{conversation}/messages', [\App\Http\Controllers\Api\AdminMessageController::class, 'store']);
// });
