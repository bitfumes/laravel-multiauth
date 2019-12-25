<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;

class AdminActiveTest extends TestCase
{
    /** @test */
    public function non_active_admin_can_not_able_to_login()
    {
        // $this->loginSuperAdmin();
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['active'=>0]);
        $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'secret123'])
        ->assertSessionHasErrors('email');
    }

    /** @test */
    public function active_admin_can_able_to_login()
    {
        $admin = $this->createAdmin(['active'=>1]);
        $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'secret'])
        ->assertRedirect(route('admin.home'));
    }
}
