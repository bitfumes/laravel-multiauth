<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }
}
