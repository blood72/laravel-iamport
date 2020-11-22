<?php

namespace Blood72\Iamport;

use Blood72\Iamport\Contracts\IamportClientContract;
use Blood72\Iamport\Payloads\Token;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

/**
 * @method \Illuminate\Http\Client\Response get(string $url, array $query = [])
 * @method \Illuminate\Http\Client\Response post(string $url, array $data = [])
 * @method \Illuminate\Http\Client\Response delete(string $url, array $data = [])
 */
class IamportClient implements IamportClientContract
{
    const BASE_URL = 'https://api.iamport.kr';
    const AUTH_URI = '/users/getToken';

    /** @var array */
    private array $config;

    /** @var \Illuminate\Support\Facades\Http */
    protected $client;

    /** @var \Blood72\Iamport\Payloads\Token */
    protected $token;

    public function __construct(array $config = [])
    {
        $this->config = $config;

        $this->token = Token::create($this->config['id']);

        $this->setBaseClient();
    }

    /**
     * @param $method
     * @param $arguments
     * @return \Illuminate\Http\Client\Response
     */
    public function __call($method, $arguments)
    {
        return $this->client->{$method}(...$arguments);
    }

    /**
     * @return self
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function auth(): self
    {
        $this->client->withToken($this->getToken(), null);

        return $this;
    }

    /**
     * @return string
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    protected function getToken()
    {
        if (! $this->token->isValid()) {
            $response = $this->client->post(static::AUTH_URI, [
                'imp_key' => $this->config['key'],
                'imp_secret' => $this->config['secret'],
            ]);

            $this->validateResponseStatus($response->status());

            $response = $response->object()->response;

            $this->token->set($response->access_token, $response->expired_at)->remember();
        }

        return $this->token->get();
    }

    /**
     * @return $this
     */
    protected function setBaseClient(): self
    {
        $this->client = Http::contentType('application/json')->baseUrl(static::BASE_URL);

        return $this;
    }

    /**
     * @param int $status
     * @return void
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    protected function validateResponseStatus(int $status): void
    {
        if ($status === Response::HTTP_NOT_FOUND) {
            throw new \Blood72\Iamport\Exceptions\IamportNotFoundException('Page not found', $status);
        } elseif ($status === Response::HTTP_UNAUTHORIZED) {
            throw new \Blood72\Iamport\Exceptions\IamportAuthException('Invalid credentials', $status);
        }
    }
}
