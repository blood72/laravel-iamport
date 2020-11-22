<?php

namespace Blood72\Iamport\Payloads;

abstract class Data
{
    /** @var object|null */
    protected ?object $data;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->data->{$name})) {
            return $this->data->{$name};
        }

        return null;
    }
}
