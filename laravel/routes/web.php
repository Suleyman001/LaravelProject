<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminDashboardController;

// Authentication routes provided by Laravel
Auth::routes();

// Debug routes - Remove in production
Route::get('/auth-check', function() {
    return [
        'is_authenticated' => auth()->check(),
        'user' => auth()->user(),
        'can_access_create' => auth()->check() && in_array(auth()->user()->role, ['user', 'admin']),
        'route_exists' => Route::has('notebooks.create'),
        'view_exists' => view()->exists('notebooks.create')
    ];
});

// Public Routes - Accessible to everyone
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authenticated Routes - Require Login
Route::middleware(['auth'])->group(function () {
    // Routes for Users and Admins
    Route::middleware(['role:user,admin', 'route.diagnose'])->group(function () {
        // Create and Store routes (must come before {notebook} routes)
        Route::get('/notebooks/create', [NotebookController::class, 'create'])
            ->name('notebooks.create');
        Route::post('/notebooks', [NotebookController::class, 'store'])
            ->name('notebooks.store');
    });

    // Notebook public routes
    Route::get('/notebooks', [NotebookController::class, 'index'])
        ->name('notebooks.index');

    Route::get('/notebooks/advanced-search', [NotebookController::class, 'advancedSearch'])
        ->name('notebooks.advanced-search');

    Route::get('/notebooks/statistics', [NotebookController::class, 'statistics'])
        ->name('notebooks.statistics');

    // User/Admin routes for existing notebooks
    Route::middleware(['role:user,admin', 'route.diagnose'])->group(function () {
        Route::get('/notebooks/{notebook}/edit', [NotebookController::class, 'edit'])
            ->name('notebooks.edit');
        Route::put('/notebooks/{notebook}', [NotebookController::class, 'update'])
            ->name('notebooks.update');
    });

    // Admin-only Routes
    Route::middleware(['role:admin'])->group(function () {
        // Delete notebook (admin-only)
        Route::delete('/notebooks/{notebook}', [NotebookController::class, 'destroy'])
            ->name('notebooks.destroy');
        
        // Admin Dashboard Routes
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // User Management
        Route::get('/admin/users', [AdminDashboardController::class, 'userManagement'])
            ->name('admin.users');

        // Delete User
        Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'deleteUser'])
            ->name('admin.users.delete');
    });

    // Dashboard for logged-in users
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Debugging Routes (Optional)
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

    Route::get('/debug-notebook-create', function() {
        $user = auth()->user();
        return [
            'authenticated' => auth()->check(),
            'user' => $user ? $user->toArray() : 'No User',
            'role' => $user ? $user->role : 'No Role',
            'can_create' => $user && in_array($user->role, ['user', 'admin'])
        ];
    })->middleware(['auth']);

    // This should be last to avoid conflicting with other notebook routes
    Route::get('/notebooks/{notebook}', [NotebookController::class, 'show'])
        ->name('notebooks.show');
});

// Debug route for development
if (config('app.debug')) {
    Route::get('/debug-all', function() {
        $user = auth()->user();
        $viewPath = resource_path('views/notebooks/create.blade.php');
        
        return [
            'auth' => [
                'is_authenticated' => auth()->check(),
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ] : null
            ],
            'view' => [
                'exists' => view()->exists('notebooks.create'),
                'path' => $viewPath,
                'file_exists' => file_exists($viewPath)
            ],
            'route' => [
                'current' => request()->route()->getName(),
                'middleware' => request()->route()->middleware()
            ],
            'models' => [
                'processor_exists' => class_exists('App\Models\Processor'),
                'operating_system_exists' => class_exists('App\Models\OperatingSystem')
            ]
        ];
    });
}