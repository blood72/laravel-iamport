<?php

namespace Blood72\Iamport\Test;

use Blood72\Iamport\Formats\Status;
use Blood72\Iamport\Payloads\CustomData;
use Blood72\Iamport\Payloads\Payment;
use Blood72\Iamport\Payloads\PreparedPayment;

class PaymentTest extends IamportTestCase
{
    /** @test */
    public function get_payments_api_should_work()
    {
        $limit = 3;

        try {
            $payments = $this->iamport->getPayments(Status::PAID, ['limit' => $limit]);

            $this->assertCount($limit, $payments);
            $this->assertPayments($payments);
            $this->assertEqualsIgnoringCase(Status::PAID, $payments->last()->status);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /** @test */
    public function get_payments_api_should_work_alternatively()
    {
        $limit = 3;

        try {
            $payments = $this->iamport->getPayments([
                'status' => Status::FAILED,
                'limit' => $limit,
            ]);

            $this->assertCount($limit, $payments);
            $this->assertPayments($payments);
            $this->assertEqualsIgnoringCase(Status::FAILED, $payments->first()->status);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /** @test */
    public function get_payment_api_should_work()
    {
        try {
            $payment = $this->iamport->getPayment('imp_993488541671'); // official testable data

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
        $this->expectException(\Blood72\Iamport\Exceptions\IamportNotFoundException::class);

        $this->iamport->getPayment('imp_no_uid_exists');
    }

    /** @test */
    public function find_payments_api_should_work()
    {
        try {
            $payments = $this->iamport->findPayments('merchant_1591933685281');

            $this->assertPayments($payments);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /** @test */
    public function find_payment_api_should_work()
    {
        try {
            $payment = $this->iamport->findPayment('merchant_1591933685281'); // official testable data

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
    public function find_payment_api_should_fail()
    {
        $this->expectException(\Blood72\Iamport\Exceptions\IamportNotFoundException::class);

        $this->iamport->findPayment('merchant_no_uid_exists'); // official testable data
    }

    /** @test */
    public function prepare_payment_apis_should_work()
    {
        $merchantUid = 'b72-' . md5(time());
        $amount = rand(1000, 100000);

        try {
            $preparedPayment = $this->iamport->setPreparePayment($merchantUid, $amount);

            $this->assertInstanceOf(PreparedPayment::class, $preparedPayment);
            $this->assertEquals($merchantUid, $preparedPayment->merchant_uid);
            $this->assertEquals($amount, $preparedPayment->amount);

            $preparedPayment = $this->iamport->getPreparePayment($merchantUid);

            $this->assertInstanceOf(PreparedPayment::class, $preparedPayment);
            $this->assertEquals($merchantUid, $preparedPayment->merchant_uid);
            $this->assertEquals($amount, $preparedPayment->amount);
        } catch (\Blood72\Iamport\Exceptions\IamportException $e) {
            $this->fail();
        }
    }

    /**
     * @param \Illuminate\Support\Collection $payments
     */
    protected function assertPayments($payments)
    {
        $this->assertInstanceOf(get_class(collect()), $payments); // Collection class
        $this->assertContainsOnlyInstancesOf(Payment::class, $payments);

        $this->assertObjectHasAttribute('total', $payments);
        $this->assertObjectHasAttribute('previous', $payments);
        $this->assertObjectHasAttribute('next', $payments);
    }
}
