<?php
namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get latest notebooks
        $latestNotebooks = Notebook::with(['processor', 'operatingSystem'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Statistics for dashboard
        $stats = [
            'total_notebooks' => Notebook::count(),
            'total_users' => User::count(),
            'total_manufacturers' => Notebook::distinct('manufacturer')->count(),
            'average_notebook_price' => Notebook::avg('price')
        ];

        // Top manufacturers distribution
        $manufacturerDistribution = Notebook::groupBy('manufacturer')
            ->select('manufacturer', DB::raw('count(*) as count'))
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Price range distribution
        $priceRanges = [
            '0-50K' => Notebook::whereBetween('price', [0, 50000])->count(),
            '50K-100K' => Notebook::whereBetween('price', [50000, 100000])->count(),
            '100K+' => Notebook::where('price', '>', 100000)->count()
        ];

        return view('home', compact(
            'latestNotebooks', 
            'stats', 
            'manufacturerDistribution',
            'priceRanges'
        ));
    }
}
