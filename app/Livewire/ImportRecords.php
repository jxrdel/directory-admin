<?php

namespace App\Livewire;

use App\Models\Directory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportRecords extends Component
{
    use WithFileUploads;

    public $records = [];
    public $count;
    public $file;
    public $file_path;
    public $isImported = false;
    public $importOption = 'import-records';
    public $nextStepDisabled = true;
    public $duplicateExtensions = [];
    public $duplicateEmployees = [];
    public $duplicateExtensionsInFile = [];
    public $duplicateEmployeesInFile = [];
    public $fileDuplicates = false;

    public function render()
    {
        return view('livewire.import-records');
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Store the file in storage and keep the path
        $this->file_path = $this->file->store('temp');

        $filePath = storage_path("app/{$this->file_path}");
        $handle = fopen($filePath, 'r');

        // Read and normalize encoding
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            unlink($filePath);
            $this->dispatch('show-error', message: 'Invalid CSV file.');
            return;
        }

        $this->duplicateExtensionsInFile = [];
        $this->duplicateEmployeesInFile = [];
        $this->fileDuplicates = false;

        $duplicatesInFile = $this->checkDuplicatesInFile($handle, $header);

        if (count($duplicatesInFile['extensions']) > 0) {
            $this->duplicateExtensionsInFile = $duplicatesInFile['extensions'];
            $this->fileDuplicates = true;
            $this->isImported = true;
            $this->nextStepDisabled = true;
            return;
        } else {
            $this->duplicateExtensionsInFile = [];
        }



        if (count($duplicatesInFile['employees']) > 0) {
            $this->duplicateEmployeesInFile = $duplicatesInFile['employees'];
            $this->fileDuplicates = true;
        } else {
            $this->duplicateEmployeesInFile = [];
        }

        rewind($handle);
        fgetcsv($handle);

        $this->duplicateExtensions = [];
        $this->duplicateEmployees = [];
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $row = array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', 'auto'), $row);
            if ($this->importOption == 'import-records') {
                if (Directory::extExists($row[5])) {
                    $this->duplicateExtensions[] = $row[5];
                }

                if (($row[3] != '') && Directory::employeeExists($row[3])) {
                    $this->duplicateEmployees[] = $row[3];
                }
            }
            $count++;
        }
        fclose($handle);

        $this->count = $count;
        $this->isImported = true;
        $this->nextStepDisabled = count($this->duplicateExtensions) > 0;
    }

    public function confirm()
    {
        if (!$this->file_path) {
            $this->dispatch('show-error', message: 'No file found for import.');
            return;
        }

        $filePath = storage_path("app/{$this->file_path}");
        $handle = fopen($filePath, 'r');

        // Read header
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            unlink($filePath);
            $this->dispatch('show-error', message: 'Invalid CSV file.');
            return;
        }

        DB::beginTransaction();

        try {
            if ($this->importOption == 'bulk-overwrite') {
                Directory::truncate();
            }

            $maxParameters = 1000; // SQL Server's max parameters
            $chunk = [];
            $totalColumns = count($header);
            $maxRowsPerBatch = intdiv($maxParameters, $totalColumns); // Calculate max rows dynamically

            while (($row = fgetcsv($handle)) !== false) {
                $row = array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', 'auto'), $row);

                if (count($row) == $totalColumns) {
                    $chunk[] = array_combine($header, $row);
                }

                // Insert only when we reach the max safe number of rows
                if (count($chunk) >= $maxRowsPerBatch) {
                    Directory::insert($this->formatRecords($chunk));
                    $chunk = []; // Reset chunk
                }
            }

            // Insert remaining records
            if (!empty($chunk)) {
                Directory::insert($this->formatRecords($chunk));
            }

            DB::commit();
            fclose($handle);
            unlink($filePath);

            $this->file_path = null;

            return redirect()->route('directory')->with('success', $this->count . ' records imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->dispatch('show-error', message: 'Error occurred while importing records: ' . $e->getMessage());
        }
    }

    private function formatRecords($records)
    {
        return array_map(function ($record) {
            return [
                'location' => $record['Floor'] ?? null,
                'department' => $record['Department'] ?? null,
                'groupname' => $record['Group Name'] ?? null,
                'employee' => $record['Employee'] ?? null,
                'extname' => $record['Extension Name'] ?? null,
                'extno' => $record['Extension Number'] ?? null,
            ];
        }, $records);
    }

    private function checkDuplicatesInFile($handle, $header)
    {
        $duplicateExtensions = [];
        $duplicateEmployees = [];
        $seenExtensions = [];
        $seenEmployees = [];

        while (($row = fgetcsv($handle)) !== false) {
            $row = array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', 'auto'), $row);

            $extno = $row[5]; // Assuming the extension number is at index 5
            $employee = $row[3]; // Assuming the employee name is at index 3

            if (in_array($extno, $seenExtensions)) {
                $duplicateExtensions[] = $extno;
            } else {
                $seenExtensions[] = $extno;
            }

            if ($employee != '' && in_array($employee, $seenEmployees)) {
                $duplicateEmployees[] = $employee;
            } elseif ($employee != '' && $employee != 'Vacant' && $employee != 'Security Officer' && $employee != 'Awaiting Placement of Officer') {
                $seenEmployees[] = $employee;
            }
        }

        return [
            'extensions' => $duplicateExtensions,
            'employees' => $duplicateEmployees
        ];
    }
}
