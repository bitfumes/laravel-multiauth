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
            'password' => 'secret',
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

    /** @test */
    public function if_admin_status_is_false_then_admin_can_not_log_in()
    {
        $admin = $this->createAdmin(['active'=>false]);
        $res   = $this->postJson(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'secret',
        ])->assertStatus(401)->json();
        $admin->update(['active'=>true]);
        $res   = $this->postJson(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'secret',
        ])->assertStatus(200)->json();
    }
}
