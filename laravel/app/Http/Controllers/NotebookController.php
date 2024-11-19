<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Models\Processor;
use App\Models\OperatingSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NotebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function create()
{
    \Log::info('Create notebook attempt', [
        'user' => auth()->user()->toArray(),
        'timestamp' => now()
    ]);

    try {
        $processors = Processor::all();
        $operatingSystems = OperatingSystem::all();

        if ($processors->isEmpty()) {
            \Log::warning('No processors found in database');
        }

        if ($operatingSystems->isEmpty()) {
            \Log::warning('No operating systems found in database');
        }

        return view('notebooks.create', compact('processors', 'operatingSystems'));
    } catch (\Exception $e) {
        \Log::error('Error in create method', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Unable to load create form. Please try again.');
    }
}
public function store(Request $request)
{
    $this->authorize('create', Notebook::class);

    $validated = $request->validate([
        'manufacturer' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'display' => 'required|numeric|min:10|max:20',
        'memory' => 'required|integer|min:2048|max:65536',
        'harddisk' => 'required|integer|min:128|max:4096',
        'videocontroller' => 'required|string|max:255',
        'price' => 'required|numeric|min:0|max:1000000',
        'processorid' => 'required|exists:processors,id',
        'opsystemid' => 'required|exists:operating_systems,id',
        'pieces' => 'required|integer|min:0|max:1000'
    ]);

    // For system-imported notebooks, set user to current admin or first admin
    $adminUser = auth()->user();
    $validated['user_id'] = $adminUser->id;

    $notebook = Notebook::create($validated);

    return redirect()->route('notebooks.show', $notebook)
        ->with('success', 'Notebook created successfully');
}

public function edit(Notebook $notebook)
{
    \Log::info('Edit Notebook Attempt', [
        'user_id' => auth()->id(),
        'user_role' => auth()->user()->role,
        'notebook_id' => $notebook->id,
        'notebook_owner_id' => $notebook->user_id
    ]);

    $this->authorize('update', $notebook);

    $processors = Processor::all();
    $operatingSystems = OperatingSystem::all();

    return view('notebooks.edit', compact('notebook', 'processors', 'operatingSystems'));
}

public function update(Request $request, Notebook $notebook)
{
    $this->authorize('update', $notebook);

    $validated = $request->validate([
        'manufacturer' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'display' => 'required|numeric',
        'memory' => 'required|integer',
        'harddisk' => 'required|integer',
        'videocontroller' => 'required|string',
        'price' => 'required|numeric',
        'processorid' => 'required|exists:processors,id',
        'opsystemid' => 'required|exists:operating_systems,id',
        'pieces' => 'required|integer'
    ]);

    $notebook->update($validated);

    return redirect()->route('notebooks.show', $notebook)
        ->with('success', 'Notebook updated successfully');
}
public function destroy(Notebook $notebook)
{
    $this->authorize('delete', $notebook);

    $notebook->delete();

    return redirect()->route('notebooks.index')
        ->with('success', 'Notebook deleted successfully');
}
    public function index(Request $request)
    {
        // Basic filtering and pagination
        $query = Notebook::query();

        // Filter by manufacturer
        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', $request->manufacturer);
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Search by type
        if ($request->filled('search')) {
            $query->where('type', 'LIKE', '%' . $request->search . '%');
        }

        // Eager load relationships
        $notebooks = $query->with(['processor', 'operatingSystem'])
            ->paginate(20);

        return view('notebooks.index', compact('notebooks'));
    }

    // app/Http/Controllers/NotebookController.php
public function show($id)
{
    $notebook = Notebook::with(['processor', 'operatingSystem', 'user'])->findOrFail($id);
    return view('notebooks.show', compact('notebook'));
}

    // Advanced statistics method
    public function statistics()
{
    // Manufacturer Distribution
    $manufacturerStats = Notebook::groupBy('manufacturer')
        ->select('manufacturer', DB::raw('count(*) as count'))
        ->get();

    // Processor Price Analysis
    $processorPriceStats = Notebook::join('processors', 'notebooks.processorid', '=', 'processors.id')
        ->groupBy('processors.type')
        ->select('processors.type', DB::raw('avg(notebooks.price) as avg_price'))
        ->get();

    // Operating System Distribution
    $osStats = Notebook::join('operating_systems', 'notebooks.opsystemid', '=', 'operating_systems.id')
        ->groupBy('operating_systems.name')
        ->select('operating_systems.name', DB::raw('count(*) as count'))
        ->get();

    return view('notebooks.statistics', [
        'manufacturerStats' => $manufacturerStats,
        'processorPriceStats' => $processorPriceStats,
        'osStats' => $osStats
    ]);
}
    // Verification method for Operating Systems
    public function verifyOperatingSystems()
    {
        $osFile = storage_path('app/data/opsystem.txt');
        $content = file_get_contents($osFile);
        $lines = explode("\n", $this->removeBOM($content));
        array_shift($lines); // Remove header

        $operatingSystems = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            $operatingSystems[] = $parts;
        }

        dd($operatingSystems);
    }

    // Method to investigate skipped lines
    public function investigateSkippedLines()
    {
        $notebookFile = storage_path('app/data/notebook.txt');
        $content = file_get_contents($notebookFile);
        $lines = explode("\n", $this->removeBOM($content));
        array_shift($lines); // Remove header

        $skippedLines = array_filter($lines, function($line) {
            $parts = explode("\t", $line);
            return count($parts) >= 10 && (intval($parts[7]) > 44 || intval($parts[8]) > 12);
        });

        dd($skippedLines);
    }

    // Utility method to remove BOM
    private function removeBOM($text) {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }
    public function advancedSearch(Request $request)
    {
        $query = Notebook::query();
    
        // Prepare filter options
        $filterOptions = [
            'manufacturers' => Notebook::distinct('manufacturer')->pluck('manufacturer'),
            'processors' => Processor::distinct('type')->pluck('type'),
            'operating_systems' => OperatingSystem::pluck('name'),
            'memory_options' => [2048, 4096, 8192, 16384],
            'harddisk_options' => [128, 256, 512, 1024],
            'price_ranges' => [
                ['min' => 0, 'max' => 50000, 'label' => 'Under 50,000'],
                ['min' => 50000, 'max' => 100000, 'label' => '50,000 - 100,000'],
                ['min' => 100000, 'max' => PHP_INT_MAX, 'label' => 'Over 100,000']
            ]
        ];
    
        // Manufacturer filter
        if ($request->filled('manufacturer')) {
            $query->where('manufacturer', $request->manufacturer);
        }
    
        // Processor filter
        if ($request->filled('processor')) {
            $query->whereHas('processor', function($q) use ($request) {
                $q->where('type', $request->processor);
            });
        }
    
        // Operating System filter
        if ($request->filled('operating_system')) {
            $query->whereHas('operatingSystem', function($q) use ($request) {
                $q->where('name', $request->operating_system);
            });
        }
    
        // Memory filter
        if ($request->filled('memory')) {
            $query->where('memory', '>=', $request->memory);
        }
    
        // Hard Disk filter
        if ($request->filled('harddisk')) {
            $query->where('harddisk', '>=', $request->harddisk);
        }
    
        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
    
        // Sorting
        $sortBy = $request->input('sort_by', 'price');
        $sortDirection = $request->input('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);
    
        // Eager load relationships and paginate
        $notebooks = $query->with(['processor', 'operatingSystem'])
            ->paginate(20);
    
        return view('notebooks.advanced-search', [
            'notebooks' => $notebooks,
            'filterOptions' => $filterOptions
        ]);
    }
}