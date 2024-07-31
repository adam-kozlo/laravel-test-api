<?php

namespace App\Http\Controllers\API;

use App\Models\AsyncAction;
use Illuminate\Http\JsonResponse;

class AsyncActionsController {
    public function getCheckStatus(AsyncAction $asyncAction) : JsonResponse {

        return response()->json([
                'type' => $asyncAction->type,
                'status' => $asyncAction->status,
                'created_at' => $asyncAction->created_at,
                'updated_at' => $asyncAction->updated_at,
            ]);
    }
}
