<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [FormController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('forms', [FormController::class, 'create'])->name('forms.create');
    Route::post('forms', [FormController::class, 'store'])->name('forms.store');
    Route::delete('forms/{name}_{id}', [FormController::class, 'destroy'])->name('forms.destroy');
    Route::patch('forms/{form}/toggle-status', [FormController::class, 'toggleStatus'])->name('forms.toggleStatus');
    Route::get('forms/{form}/answers', [FormController::class, 'showAnswers'])->name('forms.answers');
});

Route::get('forms/{name}_{id}', [FormController::class, 'show'])->name('forms.show');
Route::post('submit-form', [FormController::class, 'submitForm'])->name('forms.submit');

require __DIR__.'/auth.php';
