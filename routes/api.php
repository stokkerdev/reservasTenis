<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;

Route::apiResource('spaces', SpaceController::class);