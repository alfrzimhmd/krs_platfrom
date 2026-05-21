<?php

use App\Http\Controllers\KrsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('krs.index');
});

Route::resource('krs', KrsController::class);