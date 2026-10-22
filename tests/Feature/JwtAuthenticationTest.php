<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\APILoginController;
use App\Http\Requests\LoginRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JwtAuthenticator;

final class JwtAuthenticationTest extends TestCase
{
    public function test_tokens_are_signed_verified_and_expire(): void
    {
        $user = new User();
        $user->setAttribute('id', 123);
        $authentication = $this->app->make(JwtAuthenticator::class);
        $token = $authentication->fromUser($user);
        $payload = $authentication->setToken($token)->getPayload();

        $this->assertSame('123', (string) $payload->get('sub'));
        $this->assertGreaterThan($payload->get('iat'), $payload->get('exp'));
        $this->assertSame([], $user->getJWTCustomClaims());
    }

    public function test_invalid_credentials_do_not_issue_a_token(): void
    {
        JWTAuth::shouldReceive('attempt')->once()->andReturn(false);
        $request = LoginRequest::create('/api/user/login', 'GET', [
            'email' => 'invalid@example.invalid',
            'password' => 'test-password',
        ]);
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->index($request);

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame(['error' => 'invalid_credentials'], $response->getData(true));
    }

    public static function rejectedTokens(): array
    {
        return [
            'expired token' => [TokenExpiredException::class, 'token_expired'],
            'invalid token' => [TokenInvalidException::class, 'token_invalid'],
            'absent token' => [JWTException::class, 'token_absent'],
        ];
    }

    #[DataProvider('rejectedTokens')]
    public function test_rejected_tokens_preserve_the_existing_response(string $exceptionClass, string $responseCode): void
    {
        JWTAuth::shouldReceive('parseToken')->once()->andThrow(new $exceptionClass('test rejection'));
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->getAuthenticatedUser();

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame([$responseCode], $response->getData(true));
    }

    public function test_a_missing_token_is_rejected_without_a_database_connection(): void
    {
        $authentication = $this->app->make('tymon.jwt.auth');
        $authentication->setRequest(Request::create('/api/user/auth'));
        $controller = $this->app->make(APILoginController::class);

        $this->assertSame(401, $controller->getAuthenticatedUser()->getStatusCode());
    }

    public function test_successful_credentials_return_the_issued_token(): void
    {
        JWTAuth::shouldReceive('attempt')->once()->andReturn('test-token');
        $request = LoginRequest::create('/api/user/login', 'GET', [
            'email' => 'test@example.invalid',
            'password' => 'test-password',
        ]);
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['token' => 'test-token'], $response->getData(true));
    }

    public function test_signing_failure_returns_a_generic_error_and_safe_log_context(): void
    {
        $logger = Log::spy();
        JWTAuth::shouldReceive('attempt')->once()->andThrow(new JWTException('test signing failure'));
        $request = LoginRequest::create('/api/user/login', 'GET');
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->index($request);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame(['error' => 'could_not_create_token'], $response->getData(true));
        $logger->shouldHaveReceived('error')->once()->with('JWT signing failed', Mockery::on(
            static fn (array $context): bool => $context['event'] === 'jwt_signing_failed'
                && $context['reason'] !== ''
                && $context['correlation_id'] !== ''
                && ! array_key_exists('password', $context)
                && ! array_key_exists('token', $context),
        ));
    }

    public function test_a_verified_token_returns_its_user(): void
    {
        $user = new User();
        $user->setAttribute('id', 123);
        JWTAuth::shouldReceive('parseToken')->once()->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')->once()->andReturn($user);
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->getAuthenticatedUser();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['user' => ['id' => 123]], $response->getData(true));
    }

    public function test_a_token_for_a_deleted_user_preserves_the_existing_response(): void
    {
        JWTAuth::shouldReceive('parseToken')->once()->andReturnSelf();
        JWTAuth::shouldReceive('authenticate')->once()->andReturn(false);
        $controller = $this->app->make(APILoginController::class);
        $response = $controller->getAuthenticatedUser();

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame(['user_not_found'], $response->getData(true));
    }

    public function test_the_public_authentication_endpoint_rejects_a_missing_token(): void
    {
        $this->getJson('/api/user/auth')->assertUnauthorized();
    }
}
