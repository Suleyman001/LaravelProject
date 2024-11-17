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
        // Only users and admins can create
        return view('notebooks.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Notebook::class);

        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            // Add other validation rules
        ]);

        $notebook = auth()->user()->notebooks()->create($validated);

        return redirect()->route('notebooks.show', $notebook);
    }

    public function edit(Notebook $notebook)
    {
        $this->authorize('update', $notebook);
        return view('notebooks.edit', compact('notebook'));
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

    public function show($id)
    {
        $notebook = Notebook::with(['processor', 'operatingSystem'])->findOrFail($id);
        return view('notebooks.show', compact('notebook'));
    }

    // Advanced statistics method
    public function statistics()
    {
        $stats = [
            'total_notebooks' => Notebook::count(),
            'total_manufacturers' => Notebook::distinct('manufacturer')->count(),
            'average_price' => Notebook::avg('price'),
            'most_common_processor' => Notebook::groupBy('processorid')
                ->select('processorid', DB::raw('count(*) as count'))
                ->orderBy('count', 'desc')
                ->first()
        ];

        return view('notebooks.statistics', compact('stats'));
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
}