<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPatientAccess {

    public function handle(Request $request, Closure $next): Response {

        $patient = $request->route()->parameter('patient');
        $authUser = $request->attributes->get('authenticated_user');

        if($patient->user_id == $authUser->id) {
            return $next($request);
        } else {
            return response('Unauthorized action.', 403);
        }
    }
}
