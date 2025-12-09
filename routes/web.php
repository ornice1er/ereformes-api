<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('emails.base');
});



Route::get('/reformes/download/{filename}', function ($filename) {
    // if (! request()->hasValidSignature()) {
    //     abort(401, "Lien expiré ou invalide.");
    // }

    $path = 'temp/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, "Fichier introuvable.");
    }

    return Storage::disk('public')->download($path);
})->name('reformes.download');