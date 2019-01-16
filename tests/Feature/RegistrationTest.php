<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_register_a_new_admin_by_super_user_only()
    {
        $this->loginSuperAdmin();
        $res = $this->postJson(route('admin.register'), [
            'name'                 => 'Sarthak',
            'email'                => 'sarthak@bitfumes.com',
            'password'             => '123456',
            'password_confirmation'=> '123456',
            'role_id'              => 1
        ])
        ->assertSuccessful()->json();
        $this->assertEquals($res['data']['email'], 'sarthak@bitfumes.com');
        $this->assertDatabaseHas('admins', ['email'=>'sarthak@bitfumes.com']);
    }

    /** @test */
    public function normal_admin_can_not_create_new_admin()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $res = $this->postJson(route('admin.register'), [
            'name'                 => 'Sarthak',
            'email'                => 'sarthak@bitfumes.com',
            'password'             => '123456',
            'password_confirmation'=> '123456',
            'role_id'              => 1
        ])->assertStatus(403);
        $this->assertDatabaseMissing('admins', ['email'=>'sarthak@bitfumes.com']);
    }

    /** @test */
    public function api_can_login_admin_and_return_token()
    {
        $admin = $this->createAdmin();
        $res   = $this->postJson(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'secret'
        ])->assertSuccessful();
        $this->assertNotNull($res->original['access_token']);
    }

    /** @test */
    public function api_can_delete_admin_by_super_admin_only()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $this->assertDatabaseHas('admins', ['email' => $admin->email]);
        $this->deleteJson(route('admin.delete', $admin->id))->assertStatus(202);
        $this->assertDatabaseMissing('admins', ['email' => $admin->email]);
    }

    /** @test */
    public function normal_admin_can_not_delete_an_admin()
    {
        $this->logInAdmin();
        $admin = $this->createAdmin();
        $this->withExceptionHandling();
        $this->deleteJson(route('admin.delete', $admin->id))->assertStatus(403);
        $this->assertDatabaseHas('admins', ['email' => $admin->email]);
    }

    /** @test */
    public function api_can_update_admin_by_super_admin()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $this->patchJson(route('admin.update', $admin->id), [
            'email'  => 'sarthak@bitfumes.com',
            'name'   => 'Sarthak',
            'role_id'=> 1
        ])->assertStatus(202);
        $this->assertDatabaseHas('admins', ['email' => 'sarthak@bitfumes.com']);
    }

    /** @test */
    public function normal_admin_can_not_update_admins()
    {
        $this->logInAdmin();
        $admin = $this->createAdmin();
        $this->withExceptionHandling();
        $this->patchJson(route('admin.update', $admin->id), [
            'email'  => 'sarthak@bitfumes.com',
            'name'   => 'Sarthak',
            'role_id'=> 1
        ])->assertStatus(403);
        $this->assertDatabaseMissing('admins', ['email' => 'sarthak@bitfumes.com']);
    }

    /** @test */
    public function api_can_logout_admin_and_invalidate_token()
    {
        $admin = $this->logInAdmin();
        $res   = $this->postJson(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'secret'
        ])->assertSuccessful();
        $token    = $res->original['access_token'];

        $this->withExceptionHandling();
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->json('POST', route('admin.logout'))
        ->assertJson(['message' => 'Successfully logged out']);

        $this->assertNull(auth()->user());
    }
}
