<?php

use App\Http\Controllers\DropzoneController;
use Illuminate\Support\Facades\Route;

// Dropzone Media Ajax
Route::get('/get-media', [DropzoneController::class, 'getMedia'])->name('getMedia');
Route::post('/store-media', [DropzoneController::class, 'storeMedia'])->name('storeMedia');
Route::delete('/delete-media', [DropzoneController::class, 'destroyMedia'])->name('deleteMedia');
