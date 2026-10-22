<?php

namespace Tests\Feature;

use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class RuntimeRoutesTest extends TestCase
{
    public function test_anonymous_root_route_preserves_its_destination(): void
    {
        $this->get('/')->assertRedirect('/api');
    }

    public function test_public_entry_page_renders_without_a_database_connection(): void
    {
        $this->get('/api')->assertOk();
    }

    protected function tearDown(): void
    {
        TrustProxies::flushState();
        Request::setTrustedProxies([], 0);
        parent::tearDown();
    }

    public function test_untrusted_proxy_headers_cannot_change_the_request_scheme(): void
    {
        TrustProxies::at(['198.51.100.2']);
        $request = Request::create('http://internal.example', server: [
            'REMOTE_ADDR' => '198.51.100.1',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ]);
        $middleware = new TrustProxies();
        $response = $middleware->handle($request, static fn (Request $forwardedRequest): Response => new Response($forwardedRequest->getScheme()));

        $this->assertSame('http', $response->getContent());
    }

    public function test_an_explicitly_trusted_proxy_can_forward_the_request_scheme(): void
    {
        TrustProxies::at(['198.51.100.1']);
        $request = Request::create('http://internal.example', server: [
            'REMOTE_ADDR' => '198.51.100.1',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ]);
        $middleware = new TrustProxies();
        $response = $middleware->handle($request, static fn (Request $forwardedRequest): Response => new Response($forwardedRequest->getScheme()));

        $this->assertSame('https', $response->getContent());
    }
}
