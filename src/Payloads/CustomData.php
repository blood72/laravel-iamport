<?php

namespace Blood72\Iamport\Payloads;

class CustomData extends Data
{
    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = json_decode($data);
    }
}
