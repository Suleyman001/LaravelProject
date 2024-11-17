<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\DashboardController;

// Essential routes for your notebook application
Route::get('/notebooks', [NotebookController::class, 'index'])->name('notebooks.index');
Route::get('/notebooks/{id}', [NotebookController::class, 'show'])->name('notebooks.show');
Route::get('/notebooks/statistics', [NotebookController::class, 'statistics'])->name('notebooks.statistics');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


