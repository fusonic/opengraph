# CHANGELOG for 2.x

## 2.2.0 (2022-01-20)

* Added compatibility with Symfony ^6.0

## 2.1.0 (2021-04-16)

* PHP 8 compatibility

## 2.0.0 (2020-05-13)

* Updated PHP version constraint to 7.4 and introduced type hints on all properties/methods
* Updated `Consumer` to require a PSR-17 request factory and a PSR-18 compliant HTTP client instead of Guzzle
* Changed version constraints to support all of Symfony 3.x, 4.x and 5.x
* Updated PHPUnit to version 9.x
* Fixed issue with fallback mode when title or description is missing in HTML source
