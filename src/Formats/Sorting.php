<?php

namespace Blood72\Iamport\Formats;

use Illuminate\Support\Str;

class Sorting
{
    const STARTED = 'started';
    const PAID    = 'paid';
    const UPDATED = 'updated';
    const STARTED_DESC = '-started';
    const PAID_DESC    = '-paid';
    const UPDATED_DESC = '-updated';

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function resolve(&$value)
    {
        if (is_null($value)) {
            return $value;
        }

        $value = Str::lower($value);

        if (! self::validate($value)) {
            throw new \InvalidArgumentException('Invalid "sorting" format');
        }

        return $value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function validate($value): bool
    {
        return ! is_null($value) and in_array($value, [
            self::STARTED, self::STARTED_DESC, self::PAID, self::PAID_DESC, self::UPDATED, self::UPDATED_DESC,
        ], true);
    }
}
