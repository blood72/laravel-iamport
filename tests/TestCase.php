<?php

namespace Blood72\Iamport\Test;

use Blood72\Iamport\Facades\IamportFacade;
use Blood72\Iamport\IamportServiceProvider;
use Orchestra\Testbench\TestCase as BaseCase;

abstract class TestCase extends BaseCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [IamportServiceProvider::class];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return ['Iamport' => IamportFacade::class];
    }
}
