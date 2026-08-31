<?php

use App\Modules\Sacraments\Presentation\Controllers\SacramentController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/sacraments')->group(function () {
    Route::get('/', [SacramentController::class, 'index']);
    Route::post('/', [SacramentController::class, 'store']);
    Route::get('/member/{memberId}', [SacramentController::class, 'showByMember']);
    Route::get('/{id}', [SacramentController::class, 'show']);
    Route::put('/{id}', [SacramentController::class, 'update']);
    Route::delete('/{id}', [SacramentController::class, 'destroy']);
});
