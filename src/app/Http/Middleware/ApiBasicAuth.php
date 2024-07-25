<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ApiBasicAuth {

    public function handle(Request $request, Closure $next): Response {

        $user = User::where('email', $request->getUser())->first();

        if($user) {
            if(Hash::check($request->getPassword(), $user->password)) {
                $request->attributes->set('authenticated_user', $user);
                return $next($request);
            } else {
                return response('Unauthorized.', 401, ['WWW-Authenticate' => 'Basic']);
            }
        } else {
            return response('Unauthorized.', 401, ['WWW-Authenticate' => 'Basic']);
        }
    }
}
