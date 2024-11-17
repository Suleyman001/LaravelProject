<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Processor;
use App\Models\OperatingSystem;
use App\Models\Notebook;

class DatabaseSeeder extends Seeder
{
    private function removeBOM($text) {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }

    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        Notebook::query()->delete();
        Processor::query()->delete();
        OperatingSystem::query()->delete();

        // Import Processors and Operating Systems first
        $processors = $this->importProcessors();
        $operatingSystems = $this->importOperatingSystems();

        // Import Notebooks with more flexible strategy
        $this->importNotebooks($processors, $operatingSystems);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function importProcessors()
    {
        $processorFile = storage_path('app/data/processor.txt');
        $processorContent = file_get_contents($processorFile);
        $processorLines = explode("\n", $this->removeBOM($processorContent));
        array_shift($processorLines); // Remove header

        $processors = [];
        $importedCount = 0;
        $skippedProcessorLines = [];

        foreach ($processorLines as $lineNumber => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            
            if (count($parts) < 3) {
                $skippedProcessorLines[] = [
                    'line_number' => $lineNumber + 2,
                    'line_content' => $line,
                    'reason' => 'Insufficient parts'
                ];
                continue;
            }

            try {
                $processor = Processor::create([
                    'id' => intval($parts[0]),
                    'manufacturer' => trim($parts[1]),
                    'type' => trim($parts[2])
                ]);
                $processors[intval($parts[0])] = $processor;
                $importedCount++;
            } catch (\Exception $e) {
                $skippedProcessorLines[] = [
                    'line_number' => $lineNumber + 2,
                    'line_content' => $line,
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info("Processor Import Summary", [
            'total_imported' => $importedCount,
            'total_skipped' => count($skippedProcessorLines)
        ]);

        return $processors;
    }

    private function importOperatingSystems()
    {
        $osFile = storage_path('app/data/opsystem.txt');
        $osContent = file_get_contents($osFile);
        $osLines = explode("\n", $this->removeBOM($osContent));
        array_shift($osLines); // Remove header

        $operatingSystems = [];
        $importedOSCount = 0;
        $skippedOSLines = [];

        foreach ($osLines as $lineNumber => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            
            if (count($parts) < 2) {
                $skippedOSLines[] = [
                    'line_number' => $lineNumber + 2,
                    'line_content' => $line,
                    'reason' => 'Insufficient parts'
                ];
                continue;
            }

            try {
                $os = OperatingSystem::create([
                    'id' => intval($parts[0]),
                    'name' => trim($parts[1])
                ]);
                $operatingSystems[intval($parts[0])] = $os;
                $importedOSCount++;
            } catch (\Exception $e) {
                $skippedOSLines[] = [
                    'line_number' => $lineNumber + 2,
                    'line_content' => $line,
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info("Operating System Import Summary", [
            'total_imported' => $importedOSCount,
            'total_skipped' => count($skippedOSLines)
        ]);

        return $operatingSystems;
    }

    private function importNotebooks($processors, $operatingSystems)
{
    $notebookFile = storage_path('app/data/notebook.txt');
    $content = file_get_contents($notebookFile);
    $lines = explode("\n", $this->removeBOM($content));
    array_shift($lines); // Remove header

    $importedCount = 0;
    $skippedLines = [];
    $startTime = microtime(true);

    // First, ensure OS ID 12 exists
    if (!isset($operatingSystems[12])) {
        $operatingSystems[12] = OperatingSystem::create([
            'id' => 12,
            'name' => 'Windows XP' // Or whatever the correct name should be
        ]);
    }

    foreach ($lines as $lineNumber => $line) {
        $line = trim($line);
        if (empty($line)) continue;

        $parts = explode("\t", $line);
        
        if (count($parts) < 10) {
            $skippedLines[] = [
                'line_number' => $lineNumber + 2,
                'line_content' => $line,
                'reason' => 'Insufficient parts'
            ];
            continue;
        }

        try {
            $processorId = intval($parts[7]);
            $osId = intval($parts[8]);

            // Auto-create processor if it doesn't exist
            if (!isset($processors[$processorId])) {
                $processors[$processorId] = Processor::create([
                    'id' => $processorId,
                    'manufacturer' => 'Auto Generated',
                    'type' => 'Processor Type ' . $processorId
                ]);
            }

            // Auto-create OS if it doesn't exist
            if (!isset($operatingSystems[$osId])) {
                $operatingSystems[$osId] = OperatingSystem::create([
                    'id' => $osId,
                    'name' => 'OS Type ' . $osId
                ]);
            }

            // Create notebook with sanitized data
            Notebook::create([
                'manufacturer' => trim($parts[0]),
                'type' => trim($parts[1]),
                'display' => floatval(str_replace(',', '.', $parts[2])),
                'memory' => intval($parts[3]),
                'harddisk' => intval($parts[4]),
                'videocontroller' => trim($parts[5]),
                'price' => intval($parts[6]),
                'processorid' => $processorId,
                'opsystemid' => $osId,
                'pieces' => intval($parts[9])
            ]);

            $importedCount++;
        } catch (\Exception $e) {
            $skippedLines[] = [
                'line_number' => $lineNumber + 2,
                'line_content' => $line,
                'error' => $e->getMessage()
            ];
        }
    }

    $executionTime = number_format(microtime(true) - $startTime, 2);

    // Log import results
    Log::info("Notebook Import Summary", [
        'total_imported' => $importedCount,
        'total_skipped' => count($skippedLines),
        'execution_time' => $executionTime . " seconds"
    ]);

    if (!empty($skippedLines)) {
        Log::warning('Skipped Notebook Lines', [
            'total_skipped' => count($skippedLines),
            'details' => $skippedLines
        ]);
    }

    return $importedCount;
}
}