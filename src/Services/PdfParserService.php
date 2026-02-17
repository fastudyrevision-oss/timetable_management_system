<?php
// src/Services/PdfParserService.php

namespace App\Services;

class PdfParserService
{
    public function parseTimetable($filePath)
    {
        $pythonScript = __DIR__ . '/../../python/timetable_parser.py';

        // Escape the file path for safety
        $cmd = "python " . escapeshellarg($pythonScript) . " " . escapeshellarg($filePath) . " 2>&1";

        // Execute the python script
        $output = shell_exec($cmd);

        // Decode JSON output
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Fallback debugging
            return [
                "error" => "JSON Decode Error: " . json_last_error_msg(),
                "raw_output" => $output
            ];
        }

        return $data;
    }
}
