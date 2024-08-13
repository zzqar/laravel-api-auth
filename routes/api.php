<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);

Route::group([
    'middleware' => ['auth:api']
], function () {
    Route::get('show', [UserAuthController::class, 'show']);
    Route::get('logout', [UserAuthController::class, 'logout']);
});
