<?php

namespace Blood72\Iamport\Test;

use Blood72\Iamport\Contracts\IamportServiceContract as ServiceContract;
use Blood72\Iamport\Facades\IamportFacade;
use Blood72\Iamport\Iamport;

class FacadeTest extends TestCase
{
    /** @test */
    public function it_can_be_resolved_and_called_up(): void
    {
        $this->assertTrue(class_exists('Iamport'));

        $actual = IamportFacade::getFacadeRoot();

        $this->assertInstanceOf(ServiceContract::class, $actual);
        $this->assertInstanceOf(Iamport::class, $actual);
    }
}
