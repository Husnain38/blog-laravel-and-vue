<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome');  // This will load the app.blade.php file
})->where('any', '.*');
