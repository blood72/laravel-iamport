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

```IAMPORT_KEY```와 ```IAMPORT_SECRET``` 값이 필요합니다.<br>
It requires ```IAMPORT_KEY``` and ```IAMPORT_SECRET```.

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

## Reference

- [Iamport](https://www.iamport.kr/)
- [Iamport Docs](https://docs.iamport.kr/)
- [Iamport API](https://api.iamport.kr/)
- alliv corporation's [Iamport](https://github.com/allivcorp/Iamport)
- Modern PHP User Group's [iamport-rest-client](https://github.com/ModernPUG/iamport-rest-client-modern-php)

## License

이 패키지는 MIT 라이선스가 부여된 오프 소스 소프트웨어입니다.<br>
This package is open-sourced software licensed under the MIT license.
