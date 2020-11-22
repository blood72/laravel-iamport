<?php

namespace Blood72\Iamport\Formats;

use Illuminate\Support\Str;

class Status
{
    const READY     = 'ready';
    const PAID      = 'paid';
    const CANCELLED = 'cancelled';
    const FAILED    = 'failed';

    const SEARCH_ALL = 'all';

    /**
     * @param string $value
     * @return string
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
            self::READY, self::PAID, self::CANCELLED, self::FAILED,
        ], true);
    }
}
