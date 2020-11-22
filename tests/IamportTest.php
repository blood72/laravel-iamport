<?php

namespace Blood72\Iamport\Test;

use Blood72\Iamport\Formats\Status;
use Blood72\Iamport\Payloads\CustomData;
use Blood72\Iamport\Payloads\Payment;

class IamportTest extends TestCase
{
    /** @var \Blood72\Iamport\Iamport */
    protected $iamport;

    /** @test */
    public function get_payments_api_should_work()
    {
        try {
            $payments = $this->iamport->getPayments(Status::PAID, ['limit' => 3]);

            $this->assertCount(3, $payments);
            $this->assertEqualsIgnoringCase(Status::PAID, $payments->last()->status);

            $this->assertInstanceOf(get_class(collect()), $payments); // Collection class
            $this->assertInstanceOf(Payment::class, $payments[0]);

            $this->assertObjectHasAttribute('total', $payments);
            $this->assertObjectHasAttribute('previous', $payments);
            $this->assertObjectHasAttribute('next', $payments);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /** @test */
    public function get_payment_api_should_work()
    {
        try {
            $payment = $this->iamport->getPayment('imp_448280090638'); // official testable data

            $this->assertInstanceOf(Payment::class, $payment);
            $this->assertInstanceOf(CustomData::class, $payment->custom);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /**
     * @test
     * @throws
     */
    public function get_payment_api_should_fail()
    {
        try {
            $this->iamport->getPayment('imp_no_uid_exists');

            $this->fail();
        } catch (\Blood72\Iamport\Exceptions\IamportNotFoundException $e) {
            $this->assertTrue(true); // payment(s) not found.
        }
    }

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
