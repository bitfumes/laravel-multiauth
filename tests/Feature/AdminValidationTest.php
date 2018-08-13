<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminValidationTest extends TestCase
{
    use DatabaseMigrations;

    public function setup()
    {
        parent::setUp();
        $this->withExceptionHandling();
    }

    /**
     * @test
     */
    public function admin_mode_need_name()
    {
        $this->loginSuperAdmin();
        $response = $this->post(route('admin.register'), [
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     */
    public function admin_mode_need_email()
    {
        $this->loginSuperAdmin();
        $response = $this->post(route('admin.register'), [
            'name' => 'sarthak',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertSessionHasErrors('email');
    }

    /**
     * @test
     */
    public function admin_mode_need_password_confirmation()
    {
        $this->loginSuperAdmin();
        $response = $this->post(route('admin.register'), [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'differentPassword',
        ]);
        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     */
    public function admin_mode_need_rules()
    {
        $this->loginSuperAdmin();
        $response = $this->post(route('admin.register'), [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $response->assertSessionHasErrors('role_id');
    }

    /**
     * @test
     */
    public function while_update_admin_mode_need_validation()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $response = $this->patch(route('admin.update', $admin->id), []);
        $response->assertSessionHasErrors(['role_id', 'email', 'name']);
    }
}
