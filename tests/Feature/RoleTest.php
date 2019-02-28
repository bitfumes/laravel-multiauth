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

    /**
     * @test
     */
    public function a_super_user_can_see_create_role_page()
    {
        $this->get(route('admin.role.create'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_store_new_role()
    {
        $role = ['name' => 'editor'];
        $this->post(route('admin.role.store'), $role)
            ->assertStatus(302)
            ->assertSessionHas('message');
        $this->assertDatabaseHas('roles', $role);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_see_edit_page_for_role()
    {
        $role = factory(Role::class)->create(['name' => 'editr']);
        $this->get(route('admin.role.edit', $role->id))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_update_a_role()
    {
        $role = factory(Role::class)->create(['name' => 'editr']);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->patch(route('admin.role.update', $role->id), ['name' => 'editor'])
            ->assertStatus(302)
            ->assertSessionHas('message');
        $this->assertDatabaseHas('roles', ['name' => 'editor']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_delete_a_role()
    {
        $role = factory(Role::class)->create(['name' => 'editor']);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
        $this->delete(route('admin.role.delete', $role->id))->assertStatus(302);
        $this->assertDatabaseMissing('roles', ['name' => 'editor']);
    }
}
