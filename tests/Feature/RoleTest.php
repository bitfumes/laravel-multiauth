<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    // public function a_super_admin_can_create_roles()
    // {
    //     //
    // }

    /**
     * @test
     */
    public function name()
    {
        $this->assertTrue(true);
    }
}
