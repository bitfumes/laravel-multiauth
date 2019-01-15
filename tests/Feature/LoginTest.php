<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_can_log_in_and_get_access_token()
    {
        $admin = $this->createAdmin();
        $this->withExceptionHandling();
        $res = $this->postJson(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'secret'
        ])->assertSuccessful()->json();
        $this->assertNotNull($res['access_token']);
    }
}
