<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Tests\TestCase;
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
}
