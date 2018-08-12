<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_super_admin_can_see_admin_register_page()
    {
        $this->loginSuperAdmin();
        $this->get('/admin/register')->assertStatus(200)->assertSee('Register New Admin');
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_see_admin_register_page()
    {
        $this->logInAdmin();
        $this->get('/admin/register')->assertStatus(302)->assertRedirect('/admin/home');
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_create_new_admin()
    {
        $this->loginSuperAdmin();
        $response = $this->post('/admin/register', [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertStatus(302)->assertRedirect('/admin/show');
        $this->assertDatabaseHas('admins', ['email' => 'sarthak@gmail.com']);
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_create_new_admin()
    {
        $this->logInAdmin();
        $response = $this->post('/admin/register', [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertStatus(302)->assertRedirect('/admin/home');
        $this->assertDatabaseMissing('admins', ['email' => 'sarthak@gmail.com']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_see_all_other_admins()
    {
        $this->loginSuperAdmin();
        $newadmin = $this->createAdmin();
        $this->get('/admin/show')->assertSee($newadmin->name);
    }
}
