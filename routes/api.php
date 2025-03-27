<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkusController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function()
{
    Route::get('skus', [SkusController::class, 'getSkus']);
});


