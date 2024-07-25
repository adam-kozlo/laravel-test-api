<?php

namespace App\Http\Controllers\API;

use App\Jobs\UploadDocument;
use App\Models\AsyncAction;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PatientsController {

    private User $user;
    public function __construct(Request $request) {
        $this->user = $request->attributes->get('authenticated_user');
    }

    public function getPatients() : JsonResponse {
        $data = [
            'patients' => Patient::where('user_id', $this->user->id)
                ->with('documents')
                ->get(),
        ];
        return response()->json($data);
    }

    public function postDocument(Patient $patient, Request $request): JsonResponse {

        $validator = Validator::make($request->all(), [
            'file_name' => 'required',
            'file_content' => 'required|file|mimes:pdf|max:'.config('upload.max_file_size_kb'),
        ]);

        if($validator->fails()) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        } else {

            $asyncAction = AsyncAction::create([
               'user_id' => $this->user->id,
               'type' => 'upload_document',
               'status' => 'in_progress',
            ]);

            dispatch(new UploadDocument($request->file_name, base64_encode(file_get_contents($request->file('file_content'))), $asyncAction, $patient));

            $data = [
                'message' => 'Loading document in progress',
                'check_status_url' => route('async-action-status', ['asyncAction' => $asyncAction]),
            ];
            return response()->json($data, 201);
        }
    }


}
