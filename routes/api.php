<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\AktorController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\FilmController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login',[LoginController::class, 'authenticate']);
Route::post('register',[LoginController::class, 'register']);

// //route kategori
// Route::get('kategori', [KategoriController::class, 'index']);
// Route::post('kategori', [KategoriController::class, 'store']);
// Route::get('kategori/{id}', [KategoriController::class, 'show']);
// Route::put('kategori/{id}', [KategoriController::class, 'update']);
// Route::delete('kategori/{id}', [KategoriController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('logout', [LoginController::class, 'logout']);
    Route::resource('kategori', KategoriController::class);
    Route::resource('genre', GenreController::class);
    Route::resource('aktor', AktorController::class);
    Route::resource('film', FilmController::class);
});
