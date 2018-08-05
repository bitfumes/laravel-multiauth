<?php

namespace Tests;

use Bitfumes\Multiauth\Model\Admin;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function logInAdmin($args = [])
    {
        $admin = factory(Admin::class)->create($args);
        $this->actingAs($admin, 'admin');
    }
}
