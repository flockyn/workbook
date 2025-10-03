# Flockyn - Workbook

<p>
    <a href="https://github.com/flockyn/workbook/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/flockyn/workbook/actions/workflows/tests.yml/badge.svg"></a>
    <a href="https://packagist.org/packages/flockyn/workbook"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/flockyn/workbook"></a>
    <a href="https://packagist.org/packages/flockyn/workbook"><img alt="Latest Version" src="https://img.shields.io/packagist/v/flockyn/workbook"></a>
    <a href="/LICENSE"><img alt="License" src="https://img.shields.io/github/license/flockyn/workbook"></a>
</p>

**Workbook** is a lightweight, memory-efficient PHP library for reading, writing, and streaming spreadsheets.
It is designed to handle large datasets without exhausting memory, while keeping a clean, developer-friendly API.  
This package is designed to work seamlessly with the **[Spreadsheet Installer](https://github.com/cndrsdrmn/spreadsheet-installer)**, which helps manage the required CLI binary for import/export operations.  
The focus is on **performance, low memory usage, and developer-friendly APIs**, making it ideal for importing and exporting large Spreadsheet files in PHP.

## Features

**Workbook** is built with modern PHP practices and tools:

- PHP 8.2+
- Pre-configured testing ([PHPUnit](https://github.com/sebastianbergmann/phpunit))
- Static analysis ([PHPStan](https://github.com/phpstan/phpstan))
- Code style ([Laravel Pint](https://github.com/laravel/pint))
- Automated refactoring ([Rector](https://github.com/rectorphp/rector))
- GitHub Actions workflow for CI

## Installation

Install the package via Composer:

```shell
composer require flockyn/workbook
```

## Documentation

You can find the full documentation at [docs](/docs) directory.

## Contributing

Contributions are welcome! Please read the [contribution guidelines](https://github.com/flockyn/workbook?tab=contributing-ov-file#contributing-to-flockyn) first.

## License

**Workbook** is open-sourced software licensed under the [MIT license](/LICENSE).