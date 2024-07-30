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

    public function __construct(
        private readonly string      $fileName,
        private readonly string      $fileContent,
        private readonly AsyncAction $asyncAction,
        private readonly Patient     $patient,
    ) {}
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
