<?php

namespace App\Jobs;

use App\Models\AsyncAction;
use App\Models\Document;
use App\Models\Patient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadDocument implements ShouldQueue {

    use Queueable;

    private string $fileName;
    private string $fileContent;
    private AsyncAction $asyncAction;
    private Patient $patient;
    public function __construct(string $fileName, string $fileContent, AsyncAction $asyncAction, Patient $patient) {
        $this->fileName = $fileName;
        $this->fileContent = $fileContent;
        $this->asyncAction = $asyncAction;
        $this->patient = $patient;
    }
    public function handle(): void {

        $filePath = '/uploads/'.md5($this->fileName . microtime()).".pdf";

        try {
            Storage::disk('public')->put($filePath, base64_decode($this->fileContent));
            $this->asyncAction->update(['status' => 'success']);
            Document::create([
                'patient_id' => $this->patient->id,
                'file_name' => $this->fileName,
                'file_path' => $filePath,
            ]);
        } catch (\Exception $exception) {
            $this->asyncAction->update(['status' => 'fail', 'error_description' =>$exception->getMessage() ]);
            Log::error('Job of uploading doctors file has just failed. Planned file path: '.$filePath);
        }
    }
}
