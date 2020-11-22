<?php

namespace Blood72\Iamport\Payloads;

/**
 * @property int    $amount
 * @property string $apply_num
 * @property string $buyer_name
 * @property string $buyer_tel
 * @property string $buyer_postcode
 * @property string $buyer_email
 * @property int    $cancel_amount
 * @property mixed  $card_code
 * @property mixed  $card_name
 * @property mixed  $card_number
 * @property mixed  $card_quota
 * @property mixed  $cash_type
 * @property bool   $cash_receipt_issued
 * @property string $channel
 * @property string $currency
 * @property array  $cancel_history
 * @property array  $cancel_receipt_urls
 * @property mixed  $cancel_reason
 * @property mixed  $cancelled_at
 * @property string $custom_data
 * @property mixed  $customer_uid
 * @property mixed  $customer_uid_usage
 * @property mixed  $escrow
 * @property mixed  $failed_reason
 * @property mixed  $failed_at
 * @property string $imp_uid
 * @property string $merchant_uid
 * @property string $name
 * @property mixed  $paid_at
 * @property string $pay_method
 * @property string $pg_id
 * @property string $pg_provider
 * @property string $pg_tid
 * @property string $receipt_url
 * @property mixed  $started_at
 * @property string $status
 * @property string $user_agent
 * @property string $vbank_code
 * @property mixed  $vbank_date
 * @property string $vbank_holder
 * @property mixed  $vbank_issued_at
 * @property string $vbank_name
 * @property string $vbank_num
 * @property-read CustomData $custom
 */
class Payment extends Data
{
    /** @var CustomData */
    protected CustomData $custom;

    /**
     * @param $response
     */
    public function __construct($response)
    {
        $this->data = $response;

        $this->custom = new CustomData($this->custom_data);
    }

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        if ($name === 'custom') {
            return $this->custom;
        }

        return parent::__get($name);
    }
}
