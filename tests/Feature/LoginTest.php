<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_user_can_see_admin_login_form()
    {
        $this->get(route('admin.login'))->assertSee('password');
    }

    /**
     * @test
     */
    public function a_user_can_login_and_redirected_to_admin_home()
    {
        $admin = $this->createAdmin();
        $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'secret'])
        ->assertRedirect(route('admin.home'));
    }

    /**
     * @test
     */
    public function logged_in_admin_can_not_see_admin_login_page()
    {
        $this->logInAdmin();
        $this->get(route('admin.login'))->assertRedirect(route('admin.home'));
    }

    /**
     * @test
     */
    // public function un_authenticated_admin_can_not_see_admin_home_page()
    // {
    //     $this->withExceptionHandling();
    //     $this->get('/admin/home')->assertRedirect('/admin');
    // }

    /**
     * @test
     */
    public function after_logout_admin_is_redirected_to_admin_login_page()
    {
        $this->logInAdmin();
        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));
    }
}
