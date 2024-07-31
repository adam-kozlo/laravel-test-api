<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;

class HealthController
{
    public function getHealth() : JsonResponse {

        return response()->json([
            'status' => "OK",
        ]);
    }

}

