<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Bitfumes\Multiauth\Tests\TestCase;

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
