<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\MultiauthTestCase;
use App\User;

class LoginTest extends MultiauthTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_user_can_see_admin_login_form()
    {
        $this->get('/admin')->assertSee('password');
    }

    /**
    * @test
    */
    public function a_user_can_login_and_redirected_to_admin_home()
    {
        $user = factory(User::class)->create();
        $this->post('/admin', ['email' => $user->email, 'password' => 'secret'])
        ->assertRedirect('/admin/home');
    }

    /**
    * @test
    */
    public function logged_in_admin_can_not_see_admin_login_page()
    {
        $this->logInAdmin();
        $this->get('/admin')->assertRedirect('/admin/home');
    }

    /**
    * @test
    */
    public function un_authenticated_admin_can_not_see_admin_home_page()
    {
        $this->withExceptionHandling();
        $this->get('/admin/home')->assertRedirect('/admin');
    }

    /**
    * @test
    */
    public function after_logout_admin_is_redirected_to_admin_login_page()
    {
        $this->logInAdmin();
        $this->post('/admin/logout')->assertRedirect('/admin');
    }
}
