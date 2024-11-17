<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminDashboardController;

// Authentication routes
Auth::routes();

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/notebooks', [NotebookController::class, 'index'])->name('notebooks.index');
Route::get('/notebooks/{notebook}', [NotebookController::class, 'show'])->name('notebooks.show');
Route::get('/notebooks/statistics', [NotebookController::class, 'statistics'])->name('notebooks.statistics');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Common authenticated user routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Debugging and verification routes
    Route::get('/test-roles', function() {
        $user = auth()->user();
        return [
            'authenticated' => auth()->check(),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ];
    });

    Route::get('/role-check', function() {
        $user = auth()->user();
        $checks = [
            'is_authenticated' => auth()->check(),
            'current_user_name' => $user->name,
            'current_user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_user' => $user->isUser(),
            'can_access_admin_dashboard' => $user->isAdmin(),
            'can_create_notebook' => $user->isAdmin() || $user->isUser()
        ];
        
        return response()->json($checks);
    });

    // User routes (for both regular users and admins)
    Route::middleware(['role:user,admin'])->group(function () {
        Route::get('/notebooks/create', [NotebookController::class, 'create'])->name('notebooks.create');
        Route::post('/notebooks', [NotebookController::class, 'store'])->name('notebooks.store');
        Route::get('/notebooks/{notebook}/edit', [NotebookController::class, 'edit'])->name('notebooks.edit');
        Route::put('/notebooks/{notebook}', [NotebookController::class, 'update'])->name('notebooks.update');
    });

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        // Notebook management
        Route::delete('/notebooks/{notebook}', [NotebookController::class, 'destroy'])->name('notebooks.destroy');
        
        // Admin Dashboard Routes
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminDashboardController::class, 'userManagement'])->name('admin.users');
        Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    });

    // Dashboard route (can be accessed by logged-in users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});