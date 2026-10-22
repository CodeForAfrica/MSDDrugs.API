<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class APILoginController extends Controller
{
    private const TOKEN_EXPIRED = 'token_expired';

    private const TOKEN_INVALID = 'token_invalid';

    private const TOKEN_ABSENT = 'token_absent';

    public function __construct(private readonly JWTAuth $authentication)
    {
    }

    public function index(LoginRequest $request): JsonResponse
    {
        // Authenticate user with JWT.
        $credentials = $request->only('email', 'password');

        try {
            $token = $this->authentication->attempt($credentials);
        } catch (JWTException $exception) {
            Log::error('JWT signing failed', [
                'event' => 'jwt_signing_failed',
                'reason' => 'The JWT provider could not create a token.',
                'exception_type' => $exception::class,
                'correlation_id' => (string) Str::uuid(),
            ]);

            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        if ($token === false) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function getAuthenticatedUser(): JsonResponse
    {
        try {
            $user = $this->authentication->parseToken()->authenticate();
        } catch (TokenExpiredException $exception) {
            return $this->rejectToken($exception, self::TOKEN_EXPIRED);
        } catch (TokenInvalidException $exception) {
            return $this->rejectToken($exception, self::TOKEN_INVALID);
        } catch (JWTException $exception) {
            return $this->rejectToken($exception, self::TOKEN_ABSENT);
        }

        if ($user === false) {
            return response()->json(['user_not_found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    /** @param self::TOKEN_EXPIRED|self::TOKEN_INVALID|self::TOKEN_ABSENT $responseCode */
    private function rejectToken(JWTException $exception, string $responseCode): JsonResponse
    {
        Log::warning('JWT authentication rejected', [
            'event' => 'jwt_authentication_rejected',
            'reason' => match ($responseCode) {
                self::TOKEN_EXPIRED => 'The JWT has expired.',
                self::TOKEN_INVALID => 'The JWT signature or payload is invalid.',
                self::TOKEN_ABSENT => 'The JWT provider could not authenticate the request.',
            },
            'exception_type' => $exception::class,
            'correlation_id' => (string) Str::uuid(),
        ]);

        return response()->json([$responseCode], 401);
    }
}
