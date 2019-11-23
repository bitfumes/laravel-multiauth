<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Tests\TestCase;
use Bitfumes\Multiauth\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_admin_conected_to_any_role()
    {
        $role = factory(Role::class)->create();
        $this->assertInstanceOf(BelongsToMany::class, $role->admins());
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

    /** @test */
    public function admin_has_resource()
    {
        $role  = factory(Role::class)->create();
        $admin = $this->createAdmin();
        $admin->roles()->attach($role->id);
        $data  = new RoleResource($role);
        $this->assertArrayHasKey('admins_attached', $data->resolve());
    }

    /** @test */
    public function admin_has_resource_collection()
    {
        $roles = factory(Role::class, 2)->create();
        $admin = $this->createAdmin();
        $admin->roles()->attach($roles);
        $data = RoleResource::collection($roles);
        $this->assertEquals(2, $data->count());
    }
}
