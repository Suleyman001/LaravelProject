<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Processor;
use App\Models\OperatingSystem;
use App\Models\Notebook;

class DatabaseSeeder extends Seeder
{
    private function removeBOM($text) {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }

    private function importData($file, $model, $columns, $autoCreate = false, $adminUser = null) 
    {
        $content = file_get_contents(storage_path('app/data/' . $file));
        $lines = explode("\n", $this->removeBOM($content));
        array_shift($lines); // Remove header

        $imported = 0;
        $skippedLines = [];
        $references = [];

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            
            if (count($parts) < count($columns)) {
                $skippedLines[] = [
                    'line_number' => $lineNumber + 2,
                    'content' => $line,
                    'reason' => 'Insufficient columns: expected ' . count($columns) . ', got ' . count($parts)
                ];
                continue;
            }

            try {
                $data = [];
                foreach ($columns as $field => $index) {
                    $value = trim($parts[$index]);
                    
                    // Handle different data types
                    switch ($field) {
                        case 'id':
                        case 'memory':
                        case 'harddisk':
                        case 'price':
                        case 'pieces':
                        case 'processorid':
                        case 'opsystemid':
                            $data[$field] = intval($value);
                            break;
                        case 'display':
                            $data[$field] = floatval(str_replace(',', '.', $value));
                            break;
                        default:
                            $data[$field] = $value;
                    }
                }

                // Special handling for Notebooks to ensure foreign keys exist
                if ($model === Notebook::class) {
                    if (!isset($references['processors'][$data['processorid']])) {
                        if ($autoCreate) {
                            $processor = Processor::firstOrCreate(
                                ['id' => $data['processorid']],
                                [
                                    'manufacturer' => 'Auto Generated',
                                    'type' => 'Processor Type ' . $data['processorid']
                                ]
                            );
                            $references['processors'][$data['processorid']] = $processor;
                        } else {
                            throw new \Exception("Processor ID {$data['processorid']} not found");
                        }
                    }

                    if (!isset($references['operating_systems'][$data['opsystemid']])) {
                        if ($autoCreate) {
                            $os = OperatingSystem::firstOrCreate(
                                ['id' => $data['opsystemid']],
                                [
                                    'name' => 'OS Type ' . $data['opsystemid']
                                ]
                            );
                            $references['operating_systems'][$data['opsystemid']] = $os;
                        } else {
                            throw new \Exception("Operating System ID {$data['opsystemid']} not found");
                        }
                    }

                    // Add admin user_id for notebooks
                    if ($adminUser) {
                        $data['user_id'] = $adminUser->id;
                    }
                }

                $instance = $model::create($data);
                
                // Store reference if this is a Processor or OperatingSystem
                if ($model === Processor::class) {
                    $references['processors'][$instance->id] = $instance;
                } elseif ($model === OperatingSystem::class) {
                    $references['operating_systems'][$instance->id] = $instance;
                }

                $imported++;
            } catch (\Exception $e) {
                $skippedLines[] = [
                    'line_number' => $lineNumber + 2,
                    'content' => $line,
                    'error' => $e->getMessage()
                ];
            }
        }

        // Detailed logging
        Log::info(class_basename($model) . " Import Summary", [
            'file' => $file,
            'total_lines' => count($lines),
            'imported' => $imported,
            'skipped' => count($skippedLines)
        ]);

        if (!empty($skippedLines)) {
            Log::warning(class_basename($model) . " Skipped Records", [
                'details' => $skippedLines
            ]);
        }

        return $references;
    }

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        Notebook::query()->delete();
        Processor::query()->delete();
        OperatingSystem::query()->delete();
        User::query()->delete();

        // Create an admin user if not exists
        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@notebook.com',
            'password' => Hash::make('password'), // Change this!
            'role' => 'admin'
        ]);

        // Import reference data first
        $references = [];
        
        $references = array_merge($references, $this->importData('processor.txt', Processor::class, [
            'id' => 0,
            'manufacturer' => 1,
            'type' => 2
        ]));

        $references = array_merge($references, $this->importData('opsystem.txt', OperatingSystem::class, [
            'id' => 0,
            'name' => 1
        ]));

        // Import notebooks with auto-creation of missing references and admin user
        $this->importData('notebook.txt', Notebook::class, [
            'manufacturer' => 0,
            'type' => 1,
            'display' => 2,
            'memory' => 3,
            'harddisk' => 4,
            'videocontroller' => 5,
            'price' => 6,
            'processorid' => 7,
            'opsystemid' => 8,
            'pieces' => 9
        ], true, $adminUser);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}