<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Bitfumes\Multiauth\Model\Role;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function a_super_admin_can_see_admin_register_page()
    {
        $this->loginSuperAdmin();
        $this->get('/admin/register')->assertStatus(200)->assertSee('Register New Admin');
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_see_admin_register_page()
    {
        $this->logInAdmin();
        $this->get('/admin/register')->assertStatus(302)->assertRedirect('/admin/home');
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_create_new_admin()
    {
        $this->loginSuperAdmin();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $response = $this->post('/admin/register', [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'role_id' => $role->id
        ]);
        $response->assertStatus(302)->assertRedirect('/admin/show');
        $this->assertDatabaseHas('admins', ['email' => 'sarthak@gmail.com']);
        $this->assertDatabaseHas('admin_role', ['admin_id' => 2]);
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_create_new_admin()
    {
        $this->logInAdmin();
        $response = $this->post('/admin/register', [
            'name' => 'sarthak',
            'email' => 'sarthak@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
        $response->assertStatus(302)->assertRedirect('/admin/home');
        $this->assertDatabaseMissing('admins', ['email' => 'sarthak@gmail.com']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_see_all_other_admins()
    {
        $this->loginSuperAdmin();
        $newadmin = $this->createAdmin();
        $this->get('/admin/show')->assertSee($newadmin->name);
    }

    /**
    * @test
    */
    public function a_super_admin_can_delete_admin()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $admin->roles()->attach($role);
        $this->delete("/admin/{$admin->id}")->assertRedirect('/admin/show');
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }

    /**
    * @test
    */
    public function a_super_admin_can_see_edit_page_for_admin()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $this->get("/admin/{$admin->id}/edit")->assertSee("Edit details of {$admin->name}");
    }

    /**
    * @test
    */
    public function a_super_admin_can_update_admin_details()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $admin->roles()->attach($role);
        $this->patch("/admin/{$admin->id}", [
            'email' => 'newadmin@gmail.com',
        ])->assertRedirect('/admin/show');
        $this->assertDatabaseHas('admins', ['email' => 'newadmin@gmail.com']);
    }
}
