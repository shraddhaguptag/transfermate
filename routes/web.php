<?php

use Illuminate\Support\Facades\Route;
#Controllers
use App\Http\Controllers\XMLController;

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
    return view('welcome');
});
//file upload routes
Route::get('file-upload', [XMLController::class, 'fileUpload'])->name('file.upload');
Route::post('file-upload', [XMLController::class, 'fileUploadPost'])->name('file.upload.post');
Route::get('search', [XMLController::class, 'search'])->name('search');
