<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
 
Route::apiResource('items', ItemController::class)->only([
'index','store','update','destroy'
]);
