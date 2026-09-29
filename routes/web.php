<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;

Route::resource('siswa', SiswaController::class);
Route::resource('guru', GuruController::class);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [SiswaController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [SiswaController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [SiswaController::class, 'destroy'])->name('profile.destroy');
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
    Route::get('/siswa/cetak-pdf', [SiswaController::class, 'cetakPdf'])->name('siswa.cetak-pdf');
    Route::get('/guru/cetak-pdf', [GuruController::class, 'cetakPdf'])->name('guru.cetak-pdf');
});

require __DIR__.'/auth.php';
