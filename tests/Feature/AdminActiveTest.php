<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;

class AdminActiveTest extends TestCase
{
    /** @test */
    public function api_can_mark_admin_as_active()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin();
        $this->postJson(route('admin.activation', $admin->id));
        $this->assertTrue($admin->fresh()->active);
    }

    /** @test */
    public function api_can_mark_admin_as_de_active()
    {
        $this->loginSuperAdmin();
        $admin = $this->createAdmin(['active'=>true]);
        $this->assertTrue($admin->fresh()->active);
        $this->deleteJson(route('admin.activation', $admin->id));
        $this->assertFalse($admin->fresh()->active);
    }
}
