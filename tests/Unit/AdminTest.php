<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Model\Admin;
use Illuminate\Support\Facades\Hash;
use Bitfumes\Multiauth\Tests\TestCase;
use Bitfumes\Multiauth\Model\Permission;
use Illuminate\Support\Facades\Notification;
use Bitfumes\Multiauth\Http\Resources\AdminResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Bitfumes\Multiauth\Notifications\AdminResetPasswordNotification;

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
    public function an_admin_can_have_many_direct_permission()
    {
        $admin      = $this->createAdmin();
        $permission = $this->create_permission();
        $admin->directPermissions()->attach($permission->id);
        $this->assertInstanceOf(Permission::class, $admin->directPermissions[0]);
    }

    /**
     * @test
     */
    public function an_admin_can_have_many_permission_by_role()
    {
        $admin      = $this->createAdmin();
        $role       = $this->create_role([], 2);
        $permission = $this->create_permission([], 3);
        $role[0]->permissions()->attach($permission->pluck('id'));
        $role[1]->permissions()->attach($permission[1]->id);
        $admin->roles()->attach($role->pluck('id'));
        $this->assertInstanceOf(Permission::class, $admin->permissionsByRole()[0]);
    }

    /**
     * @test
     */
    public function an_admin_has_some_permission_either_by_direct_or_by_role()
    {
        $admin       = $this->createAdmin();
        // DB::enableQueryLog();
        $role        = $this->create_role(['name' => 'this_role']);
        $permission1 = $this->create_permission(['name' => 'permission_1']);
        $permission2 = $this->create_permission(['name' => 'permission_2']);
        $role->permissions()->attach($permission1->id);
        $admin->roles()->attach($role->id);
        $admin->directPermissions()->attach($permission2->id);
        $this->assertTrue($admin->hasPermission($permission1->name));
        $this->assertTrue($admin->hasPermission($permission2->name));
        $this->assertTrue($admin->hasPermission($permission1->id));
        // dd(DB::getQueryLog());
    }

    /**
     * @test
     */
    public function an_admin_can_add_direct_permission()
    {
        $admin       = $this->createAdmin();
        // DB::enableQueryLog();
        $permission = $this->create_permission(['name' => 'permission_1']);
        $admin->addDirectPermission($permission->id);
        $this->assertTrue($admin->hasPermission('permission_1'));
        // dd(DB::getQueryLog());
    }

    /**
     * @test
     */
    public function an_admin_can_remove_direct_permission()
    {
        $admin       = $this->createAdmin();
        // DB::enableQueryLog();
        $permission = $this->create_permission(['name' => 'permission_1']);
        $admin->addDirectPermission($permission->id);
        $this->assertTrue($admin->hasPermission('permission_1'));
        $admin->removeDirectPermission($permission->id);
        $this->assertFalse($admin->fresh()->hasPermission('permission_1'));
        // dd(DB::getQueryLog());
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
