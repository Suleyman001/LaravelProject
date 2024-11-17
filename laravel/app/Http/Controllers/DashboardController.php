<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Models\Processor;
use App\Models\OperatingSystem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_notebooks' => Notebook::count(),
            'total_processors' => Processor::count(),
            'total_operating_systems' => OperatingSystem::count(),
            
            'manufacturers' => $this->getManufacturerBreakdown(),
            'price_distribution' => $this->getPriceDistribution(),
            'top_processors' => $this->getTopProcessors(),
            'operating_system_distribution' => $this->getOSDistribution(),
        ];

        return view('dashboard', compact('stats'));
    }

    private function getManufacturerBreakdown()
    {
        return Notebook::select('manufacturer', DB::raw('count(*) as count'))
            ->groupBy('manufacturer')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getPriceDistribution()
    {
        return [
            'budget' => Notebook::where('price', '<', 50000)->count(),
            'mid_range' => Notebook::whereBetween('price', [50000, 150000])->count(),
            'high_end' => Notebook::where('price', '>', 150000)->count()
        ];
    }

    private function getTopProcessors()
    {
        return DB::table('notebooks')
            ->join('processors', 'notebooks.processorid', '=', 'processors.id')
            ->select('processors.manufacturer', 'processors.type', DB::raw('count(*) as count'))
            ->groupBy('processors.manufacturer', 'processors.type')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getOSDistribution()
    {
        return DB::table('notebooks')
            ->join('operating_systems', 'notebooks.opsystemid', '=', 'operating_systems.id')
            ->select('operating_systems.name', DB::raw('count(*) as count'))
            ->groupBy('operating_systems.name')
            ->orderBy('count', 'desc')
            ->get();
    }
}