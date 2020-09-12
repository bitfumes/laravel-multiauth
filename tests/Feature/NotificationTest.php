<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Bitfumes\Multiauth\Notifications\RegistrationNotification;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setup():void
    {
        parent::setUp();
        $this->loginSuperAdmin();
    }

    /** @test */
    public function on_registration_admin_get_an_confirmation_email()
    {
        Notification::fake();
        app()['config']->set('multiauth.registration_notification_email', true);
        $this->createNewAdminWithRole();
        $admin = Admin::find(2);
        Notification::assertSentTo([$admin], RegistrationNotification::class);
    }

    protected function createNewAdminWithRole()
    {
        $role = factory(Role::class)->create(['name' => 'editor']);

        return $this->postJson(route('admin.register'), [
            'name'                   => 'sarthak',
            'email'                  => 'sarthak@gmail.com',
            'password'               => 'secret',
            'password_confirmation'  => 'secret',
            'role_ids'               => $role->id,
        ]);
    }
}
