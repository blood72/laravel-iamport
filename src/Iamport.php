<?php

namespace Blood72\Iamport;

use Blood72\Iamport\Contracts\IamportClientContract as IamportClient;
use Blood72\Iamport\Contracts\IamportServiceContract as IamportService;
use Blood72\Iamport\Formats\IamportUid;
use Blood72\Iamport\Formats\Sorting;
use Blood72\Iamport\Formats\Status;
use Blood72\Iamport\Payloads\Payment;
use Blood72\Iamport\Payloads\PreparedPayment;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class Iamport implements IamportService
{
    const GET_PAYMENT_URI     = '/payments';
    const GET_PAYMENTS_URI    = '/payments/status';
    const FIND_PAYMENT_URI    = '/payments/find';
    const FIND_PAYMENTS_URI   = '/payments/findAll';
    const CANCEL_PAYMENT_URI  = '/payments/cancel';
    const PREPARE_PAYMENT_URI = '/payments/prepare';

    /** @var \Blood72\Iamport\Contracts\IamportClientContract */
    protected IamportClient $client;

    /**
     * @param \Blood72\Iamport\Contracts\IamportClientContract $client
     * @return void
     */
    public function __construct(IamportClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array|string|int $iamportUid
     * @return \Blood72\Iamport\Payloads\Payment|\Illuminate\Support\Collection
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function getPayment(...$iamportUid)
    {
        $iamportUid = Arr::flatten($iamportUid);

        IamportUid::resolve($iamportUid);

        [$url, $query] = [self::GET_PAYMENT_URI, []];

        count($iamportUid) <= 1
            ? $url .= '/' . head($iamportUid)
            : Arr::set($query, 'imp_uid', [...$iamportUid]);

        $response = $this->client->auth()->get($url, $query);

        $this->validateResponseStatus($response->status());

        return $this->buildPaymentsFromResponse($response->object()->response);
    }

    /**
     * @param string $status
     * @param array $options
     * @return \Illuminate\Support\Collection
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function getPayments($status = Status::SEARCH_ALL, array $options = [])
    {
        if (is_array($status)) {
            $options = $status;

            $status = $status['status'] ?? Status::SEARCH_ALL;
        }

        if ($status !== Status::SEARCH_ALL) {
            Status::resolve($status);
        }

        if (Arr::has($options, 'sorting')) {
            Arr::set($options, 'sorting', Sorting::resolve(Arr::get($options, 'sorting')));
        }

        $query = Arr::only(array_filter($options), ['page', 'sorting', 'from', 'to', 'limit']);

        $response = $this->client->auth()->get(self::GET_PAYMENTS_URI . "/$status", $query);

        $this->validateResponseStatus($response->status());

        return $this->buildPaymentsFromResponse($response->object()->response);
    }

    /**
     * @param string $merchantUid
     * @param string|null $status
     * @param array $options
     * @return \Blood72\Iamport\Payloads\Payment
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function findPayment($merchantUid, $status = null, array $options = [])
    {
        $url = self::FIND_PAYMENT_URI . "/$merchantUid";

        if (Status::resolve($status)) {
            $url .= "/$status";
        }

        $query = Arr::only(array_filter($options), ['page', 'sorting']);

        $response = $this->client->auth()->get($url, $query);

        $this->validateResponseStatus($response->status());

        return $this->buildPaymentsFromResponse($response->object()->response);
    }

    /**
     * @param string $merchantUid
     * @param string|null $status
     * @param array $options
     * @return \Illuminate\Support\Collection
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function findPayments($merchantUid, $status = null, array $options = [])
    {
        $url = self::FIND_PAYMENTS_URI . "/$merchantUid";

        if (Status::resolve($status)) {
            $url .= "/{$status}";
        }

        $query = Arr::only(array_filter($options), [
            'page', 'from', 'to', 'limit', 'sorting',
        ]);

        $response = $this->client->auth()->get($url, $query);

        $this->validateResponseStatus($response->status());

        return $this->buildPaymentsFromResponse($response->object()->response);
    }

    /**
     * @param string|array $iamportUid
     * @param int|null $amount
     * @param string|null $reason
     * @param array $options
     * @return \Blood72\Iamport\Payloads\Payment|\Illuminate\Support\Collection
     * @throws Exceptions\IamportException
     */
    public function cancelPayment($iamportUid, ?int $amount = null, ?string $reason = null, array $options = [])
    {
        if (isset($iamportUid) && is_array($iamportUid)) {
            $options = $iamportUid;
        }

        if (empty($options)) {
            Arr::set($options, 'imp_uid', $iamportUid);
            Arr::set($options, 'amount', $amount);
            Arr::set($options, 'reason', $reason);
        }

        $query = Arr::only(array_filter($options), [
            'imp_uid', 'merchant_uid',
            'amount', 'tax_free', 'checksum', 'reason',
            'refund_holder', 'refund_bank', 'refund_account',
        ]);

        $response = $this->client->auth()->post(self::CANCEL_PAYMENT_URI, $query);

        $this->validateResponseStatus($response->status());

        return $this->buildPaymentsFromResponse($response->object()->response);
    }

    /**
     * @param string $merchantUid
     * @return \Blood72\Iamport\Payloads\PreparedPayment
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function getPreparePayment($merchantUid)
    {
        $response = $this->client->auth()->get(self::PREPARE_PAYMENT_URI . "/$merchantUid");

        $this->validateResponseStatus($response->status());

        return new PreparedPayment($response->object()->response);
    }

    /**
     * @param string $merchantUid
     * @param int $amount
     * @return \Blood72\Iamport\Payloads\PreparedPayment
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    public function setPreparePayment($merchantUid, $amount)
    {
        $response = $this->client->auth()->post(self::PREPARE_PAYMENT_URI, [
            'merchant_uid' => $merchantUid,
            'amount' => $amount,
        ]);

        $this->validateResponseStatus($response->status());

        return new PreparedPayment($response->object()->response);
    }

    /**
     * @param mixed $responses
     * @return \Blood72\Iamport\Payloads\Payment|\Illuminate\Support\Collection
     */
    protected function buildPaymentsFromResponse($responses)
    {
        $payments = collect();

        if (is_object($responses) and property_exists($responses, 'list')) {
            $payments->total = $responses->total;
            $payments->next = $responses->next;
            $payments->previous = $responses->previous;

            $responses = $responses->list;
        } elseif (! is_array($responses)) {
            return new Payment($responses);
        }

        foreach ((array) $responses as $response) {
            $payments->add(new Payment($response));
        }

        return $payments;
    }

    /**
     * @param int $status
     * @return void
     * @throws \Blood72\Iamport\Exceptions\IamportException
     */
    protected function validateResponseStatus(int $status): void
    {
        if ($status === Response::HTTP_BAD_REQUEST) {
            throw new \InvalidArgumentException('Bad request', $status);
        } elseif ($status === Response::HTTP_NOT_FOUND) {
            throw new \Blood72\Iamport\Exceptions\IamportNotFoundException('Payment(s) not found', $status);
        } elseif ($status === Response::HTTP_UNAUTHORIZED) {
            throw new \Blood72\Iamport\Exceptions\IamportAuthException('Invalid access token', $status);
        }
    }
}
