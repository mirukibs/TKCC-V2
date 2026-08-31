<?php

use App\Modules\Communities\Presentation\Controllers\CommunityController;
use App\Modules\Households\Presentation\Controllers\HouseholdController;
use App\Modules\Members\Presentation\Controllers\MemberController;
use App\Modules\Zones\Presentation\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('members', MemberController::class);
Route::apiResource('households', HouseholdController::class);
Route::apiResource('communities', CommunityController::class);
Route::apiResource('zones', ZoneController::class);
