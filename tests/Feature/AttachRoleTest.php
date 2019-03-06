<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AttachRoleTest extends TestCase
{
    use DatabaseMigrations;
    public $super;
    public $editorRole;

    public function setup():void
    {
        parent::setUp();
        $this->super      = $this->loginSuperAdmin();
        $this->editorRole = factory(Role::class)->create(['name' => 'editor']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_attach_roles_to_admin()
    {
        $admin = $this->createAdmin();
        $this->post(route('admin.attach.roles', [
            'admin' => $admin->id, 'role' => $this->editorRole->id
        ]))->assertStatus(201);
        $this->assertEquals($admin->roles()->pluck('name')[0], 'editor');
        $this->assertDatabaseHas('admin_role', ['role_id'=> $this->editorRole->id]);
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_attach__roles_to_admin()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $admin = $this->createAdmin();
        $this->post(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]))
             ->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_super_user_can_detach_role_for_an_admin()
    {
        $admin = $this->createAdmin();
        $admin->roles()->attach($this->editorRole->id);
        $this->delete(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]))->assertStatus(202);
        $this->assertEmpty($admin->fresh()->roles()->count());
        $this->assertDatabaseMissing('admin_role', ['role_id' => $this->editorRole->id]);
    }
}
