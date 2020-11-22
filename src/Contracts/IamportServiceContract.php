<?php

namespace Blood72\Iamport\Contracts;

interface IamportServiceContract
{
    /**
     * @param array|string|int $iamportUid
     * @return \Blood72\Iamport\Payloads\Payment|\Illuminate\Support\Collection
     */
    public function getPayment(...$iamportUid);

    /**
     * @param string $status
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    public function getPayments(string $status, array $options = []);

    /**
     * @param string $merchantUid
     * @param string|null $status
     * @param array $options
     * @return \Blood72\Iamport\Payloads\Payment
     */
    public function findPayment($merchantUid, $status = null, array $options = []);

    /**
     * @param string $merchantUid
     * @param string|null $status
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    public function findPayments($merchantUid, $status = null, array $options = []);

    /**
     * @param string|array $iamportUid
     * @param int|null $amount
     * @param string|null $reason
     * @param array $options
     * @return \Blood72\Iamport\Payloads\Payment|\Illuminate\Support\Collection
     */
    public function cancelPayment($iamportUid, ?int $amount = null, ?string $reason = null, array $options = []);

    /**
     * @param string $merchantUid
     * @return \Illuminate\Support\Collection
     */
    public function getPreparePayment($merchantUid);

    /**
     * @param string $merchantUid
     * @param int $amount
     * @return \Illuminate\Support\Collection
     */
    public function setPreparePayment($merchantUid, $amount);
}
