<?php

namespace Blood72\Iamport\Test;

use Blood72\Iamport\Contracts\IamportServiceContract as ServiceContract;
use Blood72\Iamport\Iamport;
use Blood72\Iamport\IamportServiceProvider;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_is_possible_to_defer_a_provider()
    {
        /** @var \Illuminate\Support\ServiceProvider $provider */
        $provider = $this->getMockBuilder(IamportServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['isDeferred'])
            ->getMock();

        $actual = $provider->isDeferred();

        $this->assertTrue($actual);
    }

    /** @test */
    public function it_can_register_binding(): void
    {
        $actual = $this->app->get(ServiceContract::class);

        $this->assertInstanceOf(Iamport::class, $actual);
    }

    /** @test */
    public function it_can_register_alias(): void
    {
        $actual = $this->app->get('iamport');

        $this->assertInstanceOf(Iamport::class, $actual);
    }

    /** @test */
    public function it_can_provide_services(): void
    {
        /** @var \Illuminate\Support\ServiceProvider $provider */
        $provider = $this->getMockBuilder(IamportServiceProvider::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['provides'])
            ->getMock();

        $actual = $provider->provides();

        $this->assertContains(ServiceContract::class, $actual);
    }
}
