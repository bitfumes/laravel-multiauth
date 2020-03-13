<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Bitfumes\Multiauth\Notifications\AdminResetPasswordNotification;

class ResetPasswordTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_password_reset_link_email_can_be_sent()
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $this->post(route('admin.password.email'), ['email' => $admin->email]);
        Notification::assertSentTo([$admin], AdminResetPasswordNotification::class);
    }

    /** @test */
    public function an_admin_can_change_its_password()
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $this->post(route('admin.password.email'), ['email' => $admin->email]);
        Notification::assertSentTo([$admin], AdminResetPasswordNotification::class, function ($notification) use ($admin) {
            $token = $notification->token;
            $this->assertTrue(Hash::check('secret', $admin->password));

            $res = $this->post(route('admin.password.request'), [
                'email'                 => $admin->email,
                'password'              => 'newpassword',
                'password_confirmation' => 'newpassword',
                'token'                 => $token,
            ]);

            $this->assertTrue(Hash::check('newpassword', $admin->fresh()->password));

            return true;
        });
    }

    /** @test */
    public function admin_can_change_password_after_login()
    {
        $admin = $this->logInAdmin();
        $this->post(route('admin.password.change'), [
            'oldPassword'              => 'secret',
            'password'                 => '123456',
            'password_confirmation'    => '123456',
        ])->assertStatus(202);
        $this->assertTrue(Hash::check('123456', $admin->fresh()->password));
    }
}
