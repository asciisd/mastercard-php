# mastercard-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

The Mastercard PHP library provides convenient access to the Mastercard API from applications written in the PHP language. 
It includes a pre-defined set of classes for API resources that initialize themselves dynamically from API responses which 
makes it compatible with a wide range of versions of the Mastercard API.

## Requirements

PHP 7.1.0 and later.

## Install

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require asciisd/mastercard-php
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`json`](https://secure.php.net/manual/en/book.json.php)
- [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Usage

``` php
 Mastercard::setMerchantId('your_merchant_id');
 Mastercard::setApiKey("your_merchant_password");
 Mastercard::setApiVersion(53);

$result = \Mastercard\Session::create();
$session_id = $result->session->id;
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

If you discover any security related issues, please email aemad@asciisd.com instead of using the issue tracker.

## Credits

- [Amr Emad][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/asciisd/mastercard-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/asciisd/mastercard-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/asciisd/mastercard-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/asciisd/mastercard-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/asciisd/mastercard-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/asciisd/mastercard-php
[link-travis]: https://travis-ci.org/asciisd/mastercard-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/asciisd/mastercard-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/asciisd/mastercard-php
[link-downloads]: https://packagist.org/packages/asciisd/mastercard-php
[link-author]: https://github.com/amead
[link-contributors]: ../../contributors
