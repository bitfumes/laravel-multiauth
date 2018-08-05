<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_super_admin_can_only_create_new_admin()
    {
        $this->logInAdmin();
    }
}
