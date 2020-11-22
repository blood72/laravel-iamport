<?php

namespace Blood72\Iamport\Payloads;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Token
{
    /** @var string|int|null */
    protected $id;

    /** @var string */
    protected string $token;

    /** @var \Illuminate\Support\Carbon */
    protected Carbon $expired_at;

    /**
     * @param mixed  $id
     * @param string $token
     * @param mixed  $expired_at
     * @param string $timezone
     */
    public function __construct($id, string $token = '', $expired_at = null, $timezone = 'Asia/Seoul')
    {
        $this->id = $id;

        $this->set($token, $expired_at, $timezone);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->expired_at->isFuture();
    }

    /**
     * @param string $token
     * @param mixed  $expired_at
     * @param string $timezone
     * @return self
     */
    public function set(string $token = '', $expired_at = null, $timezone = 'Asia/Seoul'): self
    {
        $this->token = $token;

        $this->expired_at = Carbon::parse($expired_at, $timezone);

        return $this;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->token;
    }

    public function remember(): self
    {
        Cache::add(self::getCacheKey($this->id), [$this->token, $this->expired_at], $this->expired_at);

        return $this;
    }

    /**
     * @param mixed  $id
     * @param string $token
     * @param mixed  $expired_at
     * @param string $timezone
     * @return static
     */
    public static function create($id, string $token = '', $expired_at = null, $timezone = 'Asia/Seoul'): self
    {
        if (Cache::has(self::getCacheKey($id))) {
            [$token, $expired_at] = Cache::get(self::getCacheKey($id));
        }

        return new static($id, $token, $expired_at, $timezone);
    }

    /**
     * @param string|int|null $cacheKey
     * @return string
     */
    protected static function getCacheKey($cacheKey): string
    {
        return "iamport.$cacheKey.token";
    }
}
