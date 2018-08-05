<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Notifications\AdminResetPasswordNotification;
use Illuminate\Support\Facades\DB;
use Tests\MultiauthTestCase;

class ResetPasswordTest extends MultiauthTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_admin_can_see_forgot_password_form()
    {
        $this->get('admin-password/reset')->assertStatus(200);
    }

    /**
    * @test
    */
    public function a_password_reset_link_email_can_be_sent()
    {
        Notification::fake();
        $admin = factory(Admin::class)->create();
        $this->post('admin-password/email', ['email' => $admin->email]);
        Notification::assertSentTo([$admin], AdminResetPasswordNotification::class);
    }

    /**
    * @test
    */
    public function an_admin_can_see_reset_password_form()
    {
        $this->get('admin-password/reset/anytoken')->assertStatus(200);
    }

    /**
    * @test
    */
    // public function an_admin_can_enter_email_to_change_his_password()
    // {
    //     // $this->withExceptionHandling();
    //     $admin = factory(Admin::class)->create();
    //     $res = $this->post('admin-password/email', ['email' => $admin->email]);

    //     // Now reset password
    //     $this->post('admin-password/reset', [
    //         'email' => $admin->email,
    //         'password' => 'abcdef',
    //         'password_confirmation' => 'abcdef',
    //         'token' => DB::table('password_resets')->first()->token
    //     ])->assertRedirect('/admin/home');
    // }
}
