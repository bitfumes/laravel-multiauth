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

    /** @test */
    public function while_login_api_need_email_and_password()
    {
        $admin = $this->createAdmin();
        $this->withExceptionHandling();
        $res = $this->postJson(route('admin.login'))->json();
        $this->assertArrayHasKey('password', $res['errors']);
        $this->assertArrayHasKey('email', $res['errors']);
    }
}
