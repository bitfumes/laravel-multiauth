<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Notifications\RegistrationNotification;
use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

    public function setup():void
    {
        parent::setUp();
        $this->loginSuperAdmin();
    }

    /** @test */
    public function a_super_admin_can_see_admin_home_page()
    {
        $this->get(route('admin.home'))->assertOk();
    }

    /** @test */
    public function a_non_super_admin_can_also_see_admin_home_page()
    {
        $this->logInAdmin();
        $this->get(route('admin.home'))->assertOk();
    }

    /**
     * @test
     */
    public function a_super_admin_can_see_admin_register_page()
    {
        $this->get(route('admin.register'))
                ->assertStatus(200)
                ->assertSee('Register New Admin');
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_see_admin_register_page()
    {
        $this->logInAdmin();
        $this->get(route('admin.register'))
                ->assertStatus(302)
                ->assertRedirect(route('admin.home'));
    }

    /**
     * @test
     */
    public function a_super_admin_can_only_create_new_admin()
    {
        $response = $this->createNewAdminWithRole();
        $response->assertStatus(302)->assertRedirect(route('admin.show'));
        $this->assertDatabaseHas('admins', ['email' => 'sarthak@gmail.com']);
        $admin = Admin::find(2);
        $this->assertTrue(Hash::check('secret', $admin->password));
        $this->assertDatabaseHas('admin_role', ['admin_id' => 2]);
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_create_new_admin()
    {
        $this->logInAdmin();
        $response = $this->createNewAdminWithRole();
        $response->assertStatus(302)->assertRedirect(route('admin.home'));
        $this->assertDatabaseMissing('admins', ['email' => 'sarthak@gmail.com']);
    }

    /**
     * @test
     */
    public function a_super_admin_can_see_all_other_admins()
    {
        $newadmin = $this->createAdmin();
        $this->get(route('admin.show'))->assertSee($newadmin->name);
    }

    /**
     * @test
     */
    public function a_non_super_admin_can_not_see_all_other_admins()
    {
        $this->logInAdmin();
        $newadmin = $this->createAdmin();
        $this->get(route('admin.show'))
        ->assertDontSee($newadmin->name)
        ->assertRedirect(route('admin.home'));
    }

    /**
     * @test
     */
    public function a_super_admin_can_delete_admin()
    {
        $admin = $this->createAdmin();
        $role  = factory(Role::class)->create(['name' => 'editor']);
        $admin->roles()->attach($role);
        $this->delete(route('admin.delete', $admin->id))->assertRedirect(route('admin.show'));
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }

    /**
     * @test
     */
    public function a_super_admin_can_see_edit_page_for_admin()
    {
        $admin = $this->createAdmin();
        $this->get(route('admin.edit', $admin->id))->assertSee("Edit details of {$admin->name}");
    }

    /**
     * @test
     */
    public function a_super_admin_can_update_admin_details()
    {
        $admin = $this->createAdmin();
        $role  = factory(Role::class)->create(['name' => 'editor']);
        $admin->roles()->attach($role);
        $newDetails = [
            'name'    => 'newname',
            'email'   => 'newadmin@gmail.com',
            'role_id' => [1, 2],
        ];
        $this->patch(route('admin.update', $admin->id), $newDetails)->assertRedirect(route('admin.show'));
        $this->assertDatabaseHas('admins', ['email' => 'newadmin@gmail.com']);
    }

    /** @test */
    public function on_registration_admin_get_an_confirmation_email()
    {
        // $app['config']->set('multiauth.registration_notification_email', true); set on base testcase
        Notification::fake();
        $this->createNewAdminWithRole();
        $admin = Admin::find(2);
        Notification::assertSentTo([$admin], RegistrationNotification::class);
    }

    protected function createNewAdminWithRole()
    {
        $role = factory(Role::class)->create(['name' => 'editor']);

        return $this->post(route('admin.register'), [
            'name'                  => 'sarthak',
            'email'                 => 'sarthak@gmail.com',
            'password'              => 'secret',
            'password_confirmation' => 'secret',
            'role_id'               => $role->id,
        ]);
    }

    /** @test */
    public function a_super_admin_can_only_see_master_and_role_drop_down()
    {
        $this->get(route('admin.home'))->assertSee('Roles');
    }

    /** @test */
    public function a_normal_admin_can_not_see_master_and_role_drop_down()
    {
        $this->logInAdmin();
        $this->get(route('admin.home'))->assertDontSee('Roles');
    }
}
