<?php

namespace App\Http\Middleware;

use App\Http\Codes;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthX
{
    protected function makeUnauthorizedResponse() {
        return response()->json([
            "code" => Codes::NOAUTH,
            "message" => "Unauthorized"
        ]);
    }

    final protected function handleException(\Exception $exception) {
        Log::info(get_class($exception));
        return $this->makeUnauthorizedResponse();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    final public function handle(Request $request, Closure $next, string ...$abilities): Response
    {
        Auth::shouldUse('sanctum');

        $user = $request->user();

        if (is_null($user)) {
            return $this->makeUnauthorizedResponse();
        }

        foreach ($abilities as $ability) {
            if (!$user->tokenCan($ability)) {
                return response()->json([
                    'code' => Codes::NOPRIV,
                    'message' => "Not " . $ability
                ]);
            }
        }

        return $next($request);
    }
}
