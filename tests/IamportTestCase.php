<?php

namespace Blood72\Iamport\Test;

abstract class IamportTestCase extends TestCase
{
    /** @var \Blood72\Iamport\Iamport */
    protected $iamport;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->iamport = $this->app->get('iamport');
    }
}
