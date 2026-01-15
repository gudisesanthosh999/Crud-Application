<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('items'); // or your main Blade view
})->where('any', '.*');

Route::view('/', 'items');