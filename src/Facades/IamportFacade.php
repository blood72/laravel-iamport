<?php

namespace Blood72\Iamport\Facades;

use Blood72\Iamport\Contracts\IamportServiceContract as IamportService;
use Illuminate\Support\Facades\Facade;

class IamportFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return IamportService::class;
    }
}
