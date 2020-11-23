<?php

namespace Blood72\Iamport\Payloads;

/**
 * @property string $merchant_uid
 * @property int    $amount
 */
class PreparedPayment extends Data
{
    /**
     * @param $response
     */
    public function __construct($response)
    {
        $this->data = $response;
    }
}
