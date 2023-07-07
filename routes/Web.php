<?php

use libraries\Route;
use app\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

// Route::get('course/:name/:name2', function($name) {
//     return 'This is a :'. $name .' course';
// });

Route::dispatch();