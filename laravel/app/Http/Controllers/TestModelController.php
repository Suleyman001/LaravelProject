<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Processor;
use App\Models\OperatingSystem;
use App\Models\Notebook;

class TestModelController extends Controller
{
    public function testModels()
    {
        try {
            $processorCount = Processor::count();
            $osCount = OperatingSystem::count();
            $notebookCount = Notebook::count();

            return response()->json([
                'Processors' => $processorCount,
                'Operating Systems' => $osCount,
                'Notebooks' => $notebookCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}