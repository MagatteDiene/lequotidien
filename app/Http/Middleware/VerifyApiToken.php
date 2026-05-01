<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Api-Token') ?? $request->query('token');

        if (!$token) {
            return response()->json([
                'succes'  => false,
                'message' => 'Token manquant. Utilisez le header X-Api-Token ou le paramètre ?token=',
            ], 401);
        }

        $apiToken = ApiToken::where('token', $token)->first();

        if (!$apiToken || !$apiToken->estValide()) {
            return response()->json([
                'succes'  => false,
                'message' => 'Token invalide ou expiré.',
            ], 401);
        }

        return $next($request);
    }
}
