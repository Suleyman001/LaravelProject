<?php
namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    $dashboardData = $this->getDashboardData($user);
    $insights = $this->generatePersonalInsights($user);
    $recommendations = $this->getNotebookRecommendations($user);

    return view('dashboard', [
        'user' => $user,
        'stats' => $dashboardData['stats'],
        'recentNotebooks' => $dashboardData['recentNotebooks'],
        'userNotebooks' => $dashboardData['userNotebooks'],
        'topManufacturers' => $dashboardData['topManufacturers'],
        'insights' => $insights,
        'recommendations' => $recommendations,
        'userProgress' => $this->calculateUserProgress($user)
    ]);
}
    private function getDashboardData($user)
    {
        $stats = [
            'total_notebooks' => Notebook::count(),
            'user_notebooks_count' => Notebook::where('user_id', $user->id)->count(),
            'total_manufacturers' => Notebook::distinct('manufacturer')->count(),
            'average_notebook_price' => Notebook::avg('price')
        ];

        // Recent notebooks (created in last 30 days)
        $recentNotebooks = Notebook::with(['processor', 'operatingSystem'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // User's notebooks
        $userNotebooks = Notebook::where('user_id', $user->id)
            ->with(['processor', 'operatingSystem'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top manufacturers
        $topManufacturers = Notebook::groupBy('manufacturer')
            ->select('manufacturer', DB::raw('count(*) as count'))
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return [
            'stats' => $stats,
            'recentNotebooks' => $recentNotebooks,
            'userNotebooks' => $userNotebooks,
            'topManufacturers' => $topManufacturers
        ];
    }

    private function generatePersonalInsights($user)
{
    $totalNotebooks = Notebook::where('user_id', $user->id)->count();
    $totalPrice = Notebook::where('user_id', $user->id)->sum('price');
    
    $insights = [
        'total_value' => $totalPrice,
        'avg_notebook_price' => $totalNotebooks > 0 ? $totalPrice / $totalNotebooks : 0,
        'most_expensive_notebook' => Notebook::where('user_id', $user->id)
            ->orderBy('price', 'desc')
            ->first()
    ];

    return $insights;
}

private function getNotebookRecommendations($user)
{
    // Simple recommendation logic based on user's existing notebooks
    $userNotebooks = Notebook::where('user_id', $user->id)->get();
    
    $recommendationQuery = Notebook::query();
    
    // If user has notebooks, recommend similar specs
    if ($userNotebooks->isNotEmpty()) {
        $avgMemory = $userNotebooks->avg('memory');
        $avgPrice = $userNotebooks->avg('price');
        
        $recommendationQuery->where('memory', '>=', $avgMemory * 0.8)
            ->where('memory', '<=', $avgMemory * 1.2)
            ->where('price', '>=', $avgPrice * 0.8)
            ->where('price', '<=', $avgPrice * 1.2)
            ->limit(3);
    }
    
    return $recommendationQuery->get();
}


private function calculateUserProgress($user)
{
    $totalNotebooks = Notebook::count();
    $userNotebooks = Notebook::where('user_id', $user->id)->count();
    
    $progress = [
        'notebooks_added' => $userNotebooks,
        'total_notebooks' => $totalNotebooks,
        'percentage' => $totalNotebooks > 0 ? 
            round(($userNotebooks / $totalNotebooks) * 100, 2) : 0,
        'badges' => $this->calculateBadges($userNotebooks)
    ];

    return $progress;
}

private function calculateBadges($notebookCount)
{
    $badges = [
        ['name' => 'Notebook Rookie', 'threshold' => 1],
        ['name' => 'Notebook Collector', 'threshold' => 5],
        ['name' => 'Notebook Guru', 'threshold' => 10],
        ['name' => 'Notebook Master', 'threshold' => 20]
    ];

    return array_filter($badges, function($badge) use ($notebookCount) {
        return $notebookCount >= $badge['threshold'];
    });
}

}
