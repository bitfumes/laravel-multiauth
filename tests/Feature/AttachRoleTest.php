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
        $this->get(route('admin.role.create'));
        $this->post(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]));
        $this->assertEquals($admin->roles()->pluck('name')[0], 'editor');
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_attach__roles_to_admin()
    {
        $this->logInAdmin();
        $admin = $this->createAdmin();
        $this->post(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]))
             ->assertStatus(302)
             ->assertRedirect('/admin/home');
    }

    /**
     * @test
     */
    public function a_super_user_can_detach_role_for_an_admin()
    {
        $admin = $this->createAdmin();
        $this->post(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]));
        $this->assertEquals($admin->roles[0]->id, $this->editorRole->id);
        $this->delete(route('admin.attach.roles', ['admin' => $admin->id, 'role' => $this->editorRole->id]));
        $this->assertEmpty($admin->fresh()->roles()->count());
    }
}
