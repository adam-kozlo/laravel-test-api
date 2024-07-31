<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreDocument;
use App\Jobs\UploadDocument;
use App\Models\AsyncAction;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientsController {

    private User $user;

    public function __construct(Request $request) {
        $this->user = $request->attributes->get('authenticated_user');
    }

    public function getPatients(): JsonResponse {

        return response()->json([
                'patients' => Patient::where('user_id', $this->user->id)
                    ->with('documents')
                    ->get(),
            ]);
    }

    public function postDocument(Patient $patient, StoreDocument $storeDocument): JsonResponse {

        $asyncAction = AsyncAction::create([
            'user_id' => $this->user->id,
            'type' => 'upload_document',
            'status' => 'in_progress',
        ]);

        dispatch(new UploadDocument($storeDocument->file_name, base64_encode(file_get_contents($storeDocument->file('file_content'))), $asyncAction, $patient));

        return response()->json([
            'message' => 'Loading document in progress',
            'check_status_url' => route('async-action-status', ['asyncAction' => $asyncAction]),
        ], 201);
    }
}
