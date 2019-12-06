<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    public function setup():void
    {
        parent::setUp();
        $this->loginSuperAdmin();
    }

    /** @test */
    public function a_super_admin_can_fetch_all_roles()
    {
        $role       = factory(Role::class)->create(['name' => 'editor']);
        $permission = $this->create_permission();
        $role->addPermission($permission->id);
        $res        = $this->getJson(route('admin.role.index'))->assertSuccessful()->json();
        $this->assertEquals(2, count($res['data']));
        $this->assertEquals(1, count($res['data'][1]['permissions']));
    }

    /** @test */
    public function normal_admin_can_not_fetch_all_roles()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $this->getJson(route('admin.role.index'))->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_store_new_role_with_permissions()
    {
        $permissions = $this->create_permission();
        $this->post(route('admin.role.store'), ['name' => 'editor', 'permissions' => $permissions->pluck('id')])
            ->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'editor']);
        $this->assertDatabaseHas('permission_role', ['permission_id' => $permissions->id]);
    }

    /**
     * @test
     */
    public function normal_admin_can_not_only_store_new_role()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $role = ['name' => 'editor'];
        $this->post(route('admin.role.store'), $role)
            ->assertStatus(403);
        $this->assertDatabaseMissing('roles', $role);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_update_a_role()
    {
        $role        = $this->create_role(['name' => 'editr']);
        $permissions = $this->create_permission([], 2);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->patchJson(route('admin.role.update', $role->id), ['name' => 'editor', 'permissions'=>$permissions[1]->id])->assertStatus(202);

        $this->assertDatabaseHas('roles', ['name' => 'editor']);
        $this->assertDatabaseHas('permission_role', ['permission_id' => $permissions[1]->id, 'role_id' => $role->id]);
        $this->assertDatabaseMissing('permission_role', ['permission_id' => $permissions[0]->id]);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_delete_a_role_and_permission_deleted_along_with_role()
    {
        \DB::statement('PRAGMA foreign_keys=on');
        $role        = factory(Role::class)->create(['name' => 'editor']);
        $permissions = $this->create_permission();
        $role->addPermission($permissions->id);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->delete(route('admin.role.delete', $role->id))->assertStatus(204);
        $permissions->delete();
        $this->assertDatabaseMissing('roles', ['name' => 'editor']);
        $this->assertDatabaseMissing('permission_role', ['role_id' =>$role->id, 'permission_id' => $permissions->id]);
    }
}
