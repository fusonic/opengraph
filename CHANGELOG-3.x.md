# CHANGELOG for 3.x

## 3.0.0 (2024-01-13)

- Bumped the required PHP version from `^7.4 || ^8.0` to `^8.1`
- Bumped the compatible Symfony version from `^3.0 || ^4.0 || ^5.0 || ^6.0` to `^5.4 || ^6.4 || ^7.1`
- Upgraded PHPUnit from `^9.0` to `^10.5 || ^11.4` and updated tests accordingly
  - Added `composer test` script for running tests
- Installed `friendsofphp/php-cs-fixer` and applied Fusonic's code style
  - Added `composer phpcs:check` script for validating code style
  - Added `composer phpcs:fix` script for fixing code style violations
- Installed `phpstan/phpstan`, `phpstan/phpstan-deprecation-rules`, `phpstan/phpstan-phpunit` and
  `phpstan/phpstan-strict-rules` and fixed reported errors
  - Added `composer phpstan` script for validating code
- Updated documentation
- Switched to GitHub actions for automated testing
