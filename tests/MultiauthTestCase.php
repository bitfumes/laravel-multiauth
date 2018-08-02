<?php

namespace Bitfumes\Multiauth\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Bitfumes\Multiauth\Model\Admin;
use Tests\CreatesApplication;

abstract class MultiauthTestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    // public function logInAdmin($args = [])
    // {
    //     $admin = factory(Admin::class)->create($args);
    //     $this->actingAs($admin, 'admin');
    // }
}
