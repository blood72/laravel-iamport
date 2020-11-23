# Laravel Iamport

아임포트(Iamport)는 국내 주요 PG사들을 쉽게 연동할 수 있도록 도와주는 서비스입니다.<br>
Iamport is a service that helps major PG integrations easily.

이 패키지는 공식적으로 지원받는 패키지가 아닙니다.<br>
This package is not officially supported.

## Index

- [Requirement](#requirement)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Reference](#reference)
- [License](#license)

## Requirement

- PHP ^7.4
- Laravel ^7.0 | ^8.0
- Guzzle ^6.3 | ^7.0

## Installation

**composer**를 통해 설치할 수 있습니다.<br>
Install using the **composer**.

```bash
composer require blood72/laravel-iamport
```

[설정 파일](./config/iamport.php)을 배포할 수 있습니다.<br> 
You can publish [config file](./config/iamport.php).

```bash
php artisan vendor:publish --provider="Blood72\Iamport\IamportServiceProvider"
```

## Configuration

`IAMPORT_KEY`와 `IAMPORT_SECRET` 값이 필요합니다.<br>
It requires `IAMPORT_KEY` and `IAMPORT_SECRET`.

```php
// in iamport.php
'id' => env('IAMPORT_ID_CODE'), // 가맹점 식별코드 (Merchant ID)
'key' => env('IAMPORT_KEY', 'imp_apikey'),
'secret' => env('IAMPORT_SECRET', 'ekKoeW8RyKuT0zgaZsUtXXTLQ4AhPFW3ZGseDA6bkA5lamv9OqDMnxyeB9wqOsuO9W3Mx9YSJ4dTqJ3f'),
```

## Usage

Facade 혹은 resolve 메서드를 통해 사용할 수 있습니다.<br>
You can use Facade or resolve methods.

```php
$payments = app('iamport')->getPayments();
$payments = Iamport::getPayments();
```

메서드 구성은 [Reference](#reference)의 API 문서를 보면 도움이 됩니다.<br>
It is helpful to understand method for seeing API documents of [reference](#reference).

1. `getPayment(...$impUid)`
    ```php
    // same result
    $payment = Iamport::getPayment('imp_779297761907');
    $payment = app('iamport')->getPayment(282589766101); // method adds 'imp_' automatically

    // return \Illuminate\Support\Collection::class
    $payments = Iamport::getPayment('imp_604050400483', 'imp_993488541671');
    ```
2. `getPayments($status = 'all', $options = [])`
    ```php
    // same result (TODO)
    $payments = Iamport::getPayments('paid', ['limit' => 3]);
    // $payments = Iamport::getPayments([
    //     'status' => 'paid',
    //     'limit' => 3,
    // ]);
    ```
3. `findPayment($merchantUid, $status = null, array $options = [])`
    ```php
    $payment = Iamport::findPayment('merchant_1591942195747');
    ```
4. `findPayments($merchantUid, $status = null, array $options = [])`
    ```php
    $payment = Iamport::findPayment('merchant_1591942195747');
    ```
5. `setPreparePayment($merchantUid, $amount)` & `getPreparePayment($merchantUid)`
    ```php
    // return \Blood72\Iamport\Payloads\PreparedPayment::class
    $payment = Iamport::setPreparePayment('b72-ae8ea2f204bd2285c03918ec321fa4ff', 124);
    $payment = Iamport::getPreparePayment('b72-ae8ea2f204bd2285c03918ec321fa4ff');
    ```
6. `cancelPayment($impUid, ?int $amount = null, ?string $reason = null, array $options = [])`
    ```php
    // same result
    Iamport::cancelPayment($impUid, $amount, $reason);
    Iamport::cancelPayment([
        'imp_uid' => $impUid,
        'amount' => $amount,
        'reason' => $reason,
    ]);
    ```

TODO

## Reference

- [Iamport](https://www.iamport.kr/)
- [Iamport Docs](https://docs.iamport.kr/)
- [Iamport API](https://api.iamport.kr/)
- alliv corporation's [Iamport](https://github.com/allivcorp/Iamport)
- Modern PHP User Group's [iamport-rest-client](https://github.com/ModernPUG/iamport-rest-client-modern-php)

## License

이 패키지는 MIT 라이선스가 부여된 오프 소스 소프트웨어입니다.<br>
This package is open-sourced software licensed under the MIT license.
