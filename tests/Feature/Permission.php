<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Bitfumes\Multiauth\Model\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->permission = $this->create_permission();
    }

    /** @test */
    public function api_can_give_all_permission()
    {
        $this->getJson(route('permission.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_permission()
    {
        $this->getJson(route('permission.show', $this->permission->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_permission()
    {
        $permission = factory(Permission::class)->make(['title'=>'Laravel']);
        $this->postJson(route('permission.store'), $permission->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Permissions', ['title'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_permission()
    {
        $this->putJson(route('permission.update', $this->permission->id), ['title'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Permissions', ['title'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_permission()
    {
        $this->deleteJson(route('permission.destroy', $this->permission->id))->assertStatus(204);
        $this->assertDatabaseMissing('Permissions', ['title'=>$this->permission->title]);
    }
}
