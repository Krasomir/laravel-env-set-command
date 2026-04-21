# Changelog

All notable changes to `laravel-env-set-command` will be documented in this file.

## [2.1.0] - 2026-04-21

### Added
- PHPStan (via `larastan/larastan`) with `phpstan.neon.dist` at level 5
- Pint `pint.json` config with Laravel preset
- GitHub Actions workflow for static analysis (PHPStan + Pint)
- New edge case tests: value containing `=`, empty `.env` file, already-quoted values, invalid env file path

### Fixed
- Values containing `=` were incorrectly written with escaped quotes (`\"`) instead of regular quotes (`"`)
- `readKeyValuePair()` regex interpolated `$key` directly without `preg_quote()`

### Changed
- Replaced `explode()` destructuring on line 59 with `Str::after()` for clarity
- Renamed `$_key`/`$_value`/`$_envFilePath` parameters to `$rawKey`/`$rawValue`/`$rawEnvFilePath`

### Removed
- Placeholder `ExampleTest.php`

---

## [2.0.0] - 2026-04-21

### Added
- Laravel 12 and 13 support
- PHP 8.3 and 8.4 support
- Test suite covering all documented `env:set` use cases
- Updated `orchestra/testbench` to `^11.0`, `pestphp/pest` to `^4.0`, `pest-plugin-laravel` to `^4.0`, `pest-plugin-arch` to `^4.0`

### Removed
- PHP 8.1 dropped — minimum is now PHP 8.2 (PHP 8.1 reached EOL December 2023)

> **Upgrading from v1.x:** if your project uses PHP 8.1 or Laravel 9, stay on `^1.0`. Otherwise update to `"krasomir/laravel-env-set-command": "^2.0"`.

---

## [1.1.0] - 2024-03-01

### Added
- Laravel 11 support
- Improved error handling and command argument parsing

### Changed
- Updated dependencies and test setup for Testbench v9

---

## [1.0.0] - 2023-11-01

### Added
- Initial release — forked and modernised from [imliam/laravel-env-set-command](https://github.com/imliam/laravel-env-set-command)
- Laravel 10 support
- `env:set` Artisan command for setting `.env` variables
