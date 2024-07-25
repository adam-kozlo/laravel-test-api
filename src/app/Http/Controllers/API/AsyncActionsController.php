<?php

namespace App\Http\Controllers\API;

use App\Models\AsyncAction;
use Illuminate\Http\JsonResponse;

class AsyncActionsController {
    public function getCheckStatus(AsyncAction $asyncAction) : JsonResponse {

        $data = [
            'type' => $asyncAction->type,
            'status' => $asyncAction->status,
            'created_at' => $asyncAction->created_at,
        ];
        return response()->json($data);
    }
}
