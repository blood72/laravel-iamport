<?php

namespace Blood72\Iamport\Contracts;

/**
 * @method static \Illuminate\Http\Client\Response get(string $url, array $query = [])
 * @method static \Illuminate\Http\Client\Response post(string $url, array $data = [])
 * @method static \Illuminate\Http\Client\Response delete(string $url, array $data = [])
 */
interface IamportClientContract
{
    /**
     * @return self
     */
    public function auth(): self;
}
