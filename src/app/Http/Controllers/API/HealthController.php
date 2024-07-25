<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;

class HealthController
{
    public function getHealth() : JsonResponse {

        $data = [
            'status' => 'OK',
        ];
        return response()->json($data);
    }

}
