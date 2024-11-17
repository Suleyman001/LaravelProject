<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notebook;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalNotebooks = Notebook::count();
        $recentUsers = User::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsers', 'totalNotebooks', 'recentUsers'));
    }

    public function userManagement()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
}