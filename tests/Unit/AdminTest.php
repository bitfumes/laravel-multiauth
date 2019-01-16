<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Notifications\AdminResetPasswordNotification;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Bitfumes\Multiauth\Http\Resources\AdminResource;
use Bitfumes\Multiauth\Model\Admin;

class AdminTest extends TestCase
{
    /**
     * @test
     */
    public function an_admin_can_have_many_role()
    {
        $admin = $this->createAdmin();
        $this->assertInstanceOf(BelongsToMany::class, $admin->roles());
    }

    /**
     * @test
     */
    public function it_can_bcrypt_the_password()
    {
        $admin = $this->createAdmin();
        $this->assertTrue(Hash::check('secret', $admin->password));
    }

    /**
     * @test
     */
    public function it_can_send_password_reset_notification()
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $admin->sendPasswordResetNotification('fakeToken');
        Notification::assertSentTo([$admin], AdminResetPasswordNotification::class);
    }

    /** @test */
    public function admin_has_resource()
    {
        $admin = $this->loginSuperAdmin();
        $data  = new AdminResource($admin);
        $this->assertArrayHasKey('roles', $data->resolve());
    }

    /** @test */
    public function admin_has_resource_collection()
    {
        $admins = $this->createAdmin([], 2);
        $data   = AdminResource::collection(Admin::all());
        $this->assertEquals(2, $data->count());
    }
}
