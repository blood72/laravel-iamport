<?php

namespace Blood72\Iamport\Formats;

use Illuminate\Support\Str;

class IamportUid implements Format
{
    /** @var string */
    const PREFIX = 'imp';

    /**
     * @param array|string|int $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function resolve(&$value)
    {
        if (is_array($value)) {
            foreach ($value as &$item) {
                self::resolve($item);
            }

            return $value;
        }

        if (is_numeric($value)) {
            $value = self::PREFIX . "_$value";
        }

        if (! self::validate($value)) {
            throw new \InvalidArgumentException('Invalid "imp_uid" format');
        }

        return $value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function validate($value): bool
    {
        return Str::startsWith($value, self::PREFIX);
    }
}
