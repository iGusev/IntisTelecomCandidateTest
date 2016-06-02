# IntisTelecomCandidateTest

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

1. Разработать скрипт , принимающий курсы валют из двух источников и обновляющий данные в таблице mysql. Без использования каких либо фреймворков.

Таблица:
--------
currency
- symbol (код валюты)
- rate (курс валюты)

Источники:
----------
- http://localhost/rates1.json
{ "rates" : [{ "symbol": "USD", "rate":1 }, { "symbol": "EUR", "rate":2 }, { "symbol": "RUR", "rate":3 }] }
- Локальный файл rates.json, содержащий
[ { "USD": 1 }, { "EUR": 2 }, { "RUR": 3 } ]
- источники необходимо обеспечить самостоятельно
- выбор варианта обновления должен осуществляться параметром при запуске скрипта из консоли (например php update.php 1/2, где 1 или 2 - выбор варианта обновления)

2. Написать юнит-тесты для разработанного кода

## Install

Via Composer

``` bash
$ composer require iGusev/IntisTelecomCandidateTest
```

## Usage

``` php
$skeleton = new League\Skeleton();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mail@igusev.ru instead of using the issue tracker.

## Credits

- [Ilya Gusev][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/iGusev/IntisTelecomCandidateTest.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/iGusev/IntisTelecomCandidateTest/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/iGusev/IntisTelecomCandidateTest.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/iGusev/IntisTelecomCandidateTest.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/iGusev/IntisTelecomCandidateTest.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/iGusev/IntisTelecomCandidateTest
[link-travis]: https://travis-ci.org/iGusev/IntisTelecomCandidateTest
[link-scrutinizer]: https://scrutinizer-ci.com/g/iGusev/IntisTelecomCandidateTest/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/iGusev/IntisTelecomCandidateTest
[link-downloads]: https://packagist.org/packages/iGusev/IntisTelecomCandidateTest
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
