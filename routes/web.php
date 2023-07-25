<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoUploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('Home/HomePage');
});

Route::post('/photos/upload', [PhotoUploadController::class, 'upload'])->name('photos.upload');

Route::post('/photos/process', [PhotoUploadController::class, 'process'])->name('photos.process');

Route::post('/photos/download', [PhotoUploadController::class, 'downloadImages'])->name('photos.download');