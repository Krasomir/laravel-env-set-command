# This package create command to set value to .env key.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/krasomir/laravel-env-set-command.svg?style=flat-square)](https://packagist.org/packages/krasomir/laravel-env-set-command)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/krasomir/laravel-env-set-command/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/krasomir/laravel-env-set-command/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/krasomir/laravel-env-set-command/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/krasomir/laravel-env-set-command/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/krasomir/laravel-env-set-command.svg?style=flat-square)](https://packagist.org/packages/krasomir/laravel-env-set-command)

This is my first Laravel package inspired (copied and adopted to L10) by package of Liam Hammett ['laravel-env-set-command'](https://github.com/imliam/laravel-env-set-command) which is not maintained anymore. 


## Installation

You can install the package via composer:

```bash
composer require krasomir/laravel-env-set-command
```

## Usage

When running the `env:set` artisan command, you must provide both a key and value as two arguments.

```bash
$ php artisan env:set app_name Example
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'Example'
```

You can also set values with spaces by wrapping them in quotes.

```bash
$ php artisan env:set app_name "Example App"
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to '"Example App"'
```

The command will also create new environment variables if an existing one does not exist.

```bash
$ php artisan env:set editor=vscode
# A new environment variable with key 'EDITOR' has been set to 'vscode'
```

Instead of two arguments split by a space, you can also mimic the `.env` file format by supplying `KEY=VALUE`.

```bash
$ php artisan env:set app_name=Example
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'Example'
```

The command will do its best to stop any invalid inputs.

```bash
$ php artisan env:set @pp_n@me Laravel
# Invalid environment key @pp_n@me! Only use letters and underscores
```

You can specify the external `.env` file in the third optional argument.

```bash
$ php artisan env:set APP_NAME TestApp /var/www/my_own_env.env
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'TestApp'
```

Or in the second parameter if you use key=value syntax.
```bash
$ php artisan env:set APP_NAME=TestApp /var/www/my_own_env.env
# Environment variable with key 'APP_NAME' has been changed from 'Laravel' to 'TestApp'
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Krasomir](https://github.com/Krasomir)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
