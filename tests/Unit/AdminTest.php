<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminTest extends TestCase
{
    /**
     * @test
     */
    public function an_admin_can_have_many_role()
    {
        $admin = $this->createAdmin();
        $this->assertInstanceOf(BelongsToMany::class, $admin->roles());
    }

    /**
     * @test
     */
    public function name()
    {
        $this->assertTrue(true);
    }
}
