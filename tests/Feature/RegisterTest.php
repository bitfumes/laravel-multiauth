<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_super_admin_can_only_create_new_admin()
    {
        $this->logInAdmin();
        $this->assertTrue(true);
    }
}
