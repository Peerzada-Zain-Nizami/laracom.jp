<?php

namespace App\Http\Controllers;

use App\Events\CodeExecuted;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Broadcast;

class CodeExecutionController extends Controller
{
    public function execute(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'language' => 'required|string',
            'code' => 'required|string',
        ]);

        $language = $request->language;
        $code = $request->code;

        // Supported languages and commands
        $commands = [
            'php' => ['file' => 'script.html', 'command' => 'E:\xampp\php\php.exe'],
        ];

        // Check if the requested language is supported
        if (!isset($commands[$language])) {
            return response()->json(['error' => 'Unsupported language'], 400);
        }

        // Save the code to a temporary file
        $filePath = storage_path("app/code/{$commands[$language]['file']}");
        file_put_contents($filePath, $code);

        // Execute the code
        $process = new Process([$commands[$language]['command'], $filePath]);
        $process->setTimeout(10);

        try {
            $process->mustRun();
            $output = $process->getOutput();

            // Broadcast the output for real-time use
            broadcast(new CodeExecuted($output));

            return response()->json(['output' => $output]);
        } catch (ProcessFailedException $e) {
            $error = $e->getMessage();

            // Broadcast the error for real-time use
            broadcast(new CodeExecuted($error));

            return response()->json(['error' => $error], 500);
        }
    }
}

