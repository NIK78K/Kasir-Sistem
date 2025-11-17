<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheControlHeaders
{
    /**
     * Add sensible Cache-Control headers for HTML/JSON while leaving static assets to web server.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');

        // Only set for dynamic responses; assets are handled by web server (.htaccess)
        if (str_contains($contentType, 'text/html') || str_contains($contentType, 'application/json')) {
            // Avoid caching dynamic pages to prevent stale UI; allow browser revalidation
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
