<?php

use App\Modules\Communities\Presentation\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;

Route::apiResource('communities', CommunityController::class);
