<?php

namespace Blood72\Iamport\Formats;

interface Format
{
    /**
     * @param $value
     * @return mixed
     */
    public static function resolve(&$value);

    /**
     * @param $value
     * @return bool
     */
    public static function validate($value): bool;
}
