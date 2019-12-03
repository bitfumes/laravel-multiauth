<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Tests\TestCase;
use Bitfumes\Multiauth\Model\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_admin_conected_to_any_role()
    {
        $role = $this->create_role();
        $this->assertInstanceOf(BelongsToMany::class, $role->admins());
    }

    /**
     * @test
     */
    public function it_has_permission_conected_to_any_role()
    {
        $role       = factory(Role::class)->create();
        $permission = $this->create_permission();
        $role->permissions()->attach($role->id);
        $this->assertInstanceOf(Permission::class, $role->permissions[0]);
    }

    /**
     * @test
     */
    public function it_can_add_permission_to_any_role()
    {
        $role       = factory(Role::class)->create();
        $permission = $this->create_permission();
        $role->addPermission($permission->pluck('id'));
        $this->assertInstanceOf(Permission::class, $role->permissions[0]);
    }

    /**
     * @test
     */
    public function it_can_remove_permission_to_role()
    {
        $role       = factory(Role::class)->create();
        $permission = $this->create_permission([], 3);
        $role->addPermission($permission->pluck('id'));
        $this->assertInstanceOf(Permission::class, $role->permissions[0]);
        $role->removePermission($permission->pluck('id'));
        $this->assertEquals($role->fresh()->permissions->count(), 0);
    }

    /**
     * @test
     */
    public function it_can_check_if_role_has_permission()
    {
        $role       = factory(Role::class)->create();
        $permission = $this->create_permission([], 3);
        $role->addPermission($permission->pluck('id'));
        $this->assertTrue($role->hasPermission($permission[0]->name));
        $this->assertTrue($role->hasPermission($permission[0]->id));
    }

    /**
     * @test
     */
    public function a_role_can_not_be_deleted_if_has_admins()
    {
        $role = factory(Role::class)->create(['name' => 'editor']);
        $this->createAdmin()->roles()->attach($role->id);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->expectException(\Exception::class);
        $role->delete();
    }

    /** @test */
    public function a_role_must_saved_in_lower_cased()
    {
        $role = factory(Role::class)->create(['name' => 'Editor']);
        $this->assertEquals($role->name, 'editor');
    }
}
