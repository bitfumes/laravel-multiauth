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
        $role = factory(Role::class)->create(['name' => 'editor']);
        $res  = $this->postJson(route('admin.role.index'))->assertSuccessful()->json();
        $this->assertEquals(2, count($res['data']));
    }

    /** @test */
    public function normal_admin_can_not_fetch_all_roles()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $this->postJson(route('admin.role.index'))->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_store_new_role()
    {
        $role = ['name' => 'editor'];
        $this->post(route('admin.role.store'), $role)
            ->assertStatus(201);
        $this->assertDatabaseHas('roles', $role);
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
        $role = factory(Role::class)->create(['name' => 'editr']);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->patchJson(route('admin.role.update', $role->id), ['name' => 'editor'])
            ->assertStatus(202);
        $this->assertDatabaseHas('roles', ['name' => 'editor']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_delete_a_role()
    {
        $role = factory(Role::class)->create(['name' => 'editor']);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->delete(route('admin.role.delete', $role->id))->assertStatus(204);
        $this->assertDatabaseMissing('roles', ['name' => 'editor']);
    }
}
