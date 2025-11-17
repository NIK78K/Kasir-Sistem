<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF token verification for testing
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Start a session for tests
        $this->startSession();
    }
}
