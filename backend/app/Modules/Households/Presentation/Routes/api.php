<?php

use App\Modules\Households\Presentation\Controllers\HouseholdController;
use Illuminate\Support\Facades\Route;

Route::apiResource('households', HouseholdController::class);
