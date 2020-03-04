<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Helpers;

class AuthorizationMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $auth = $request->header("authorization");

    if (!$auth) {
      return response()->json([
        "error"         => 'Unauthorized',
        "success"       => false, 
        "message"       => "missing authorization token"
        ],
        401
      );
    }

    $decoded_token = Helpers::decodeJWT($auth);

    if (!$decoded_token) {
      return response()->json([
        "error"         => 'Unauthorized',
        "success"       => false, 
        "message"       => "invalid authorization token"
        ],
        401
      );
    }
    
    $request->token = $decoded_token;

    return $next($request);

  }
}
