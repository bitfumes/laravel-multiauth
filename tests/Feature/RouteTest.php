<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RouteTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_invalid_route_return_with_404()
    {
        $this->withExceptionHandling();
        $this->logInAdmin();
        $this->get('/admin/home')->assertOk();
        $this->get('/admin/invalidRoute')->assertStatus(404);
    }
}
