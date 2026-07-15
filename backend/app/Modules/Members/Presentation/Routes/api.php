<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Members\Presentation\Controllers\MemberController;

Route::prefix('members')->group(function () {
    Route::get('/', [MemberController::class, 'index']);
    Route::post('/', [MemberController::class, 'store']);
    Route::get('/{id}', [MemberController::class, 'show']);
});
