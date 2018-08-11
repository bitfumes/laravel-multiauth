<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Bitfumes\Multiauth\Model\Admin;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_super_admin_can_see_admin_page()
    {
        $this->logInAdmin();
        $this->get('/admin/register')->assertStatus(200)->assertSee('Register New Admin');
    }

    /**
    * @test
    */
    public function a_super_admin_can_only_create_new_admin()
    {
        $this->logInAdmin();
        $this->get('/admin/register');
        $response = $this->post('/admin/register', [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $response->assertStatus(302)->assertRedirect('/admin/register');
    }

    /**
    * @test
    */
    public function a_super_admin_can_see_all_other_admins()
    {
        $this->logInAdmin();
        $newadmin = $this->createAdmin();
        $this->get('/admin/show-all')->assertSee($newadmin->name);
    }
}
