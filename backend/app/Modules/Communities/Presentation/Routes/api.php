<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Communities\Presentation\Controllers\CommunityController;

Route::apiResource('communities', CommunityController::class);
