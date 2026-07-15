<?php

use App\Modules\Members\Presentation\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::prefix('members')->group(function () {
    Route::get('/', [MemberController::class, 'index']);
    Route::post('/', [MemberController::class, 'store']);
    Route::get('/{id}', [MemberController::class, 'show']);
});
