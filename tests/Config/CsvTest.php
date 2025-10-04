<?php

declare(strict_types=1);

namespace Tests\Config;

use Flockyn\Workbook\Config\Csv;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CsvTest extends TestCase
{
    public static function dataProviderCustomOptions(): array
    {
        $options = [
            'delimiter' => ';',
            'comment' => '#',
            'lazy_quotes' => true,
            'trim_leading_space' => true,
            'batch_size' => 100,
        ];

        return [
            'constructed' => [
                new Csv(
                    batchSize: $options['batch_size'],
                    delimiter: $options['delimiter'],
                    comment: $options['comment'],
                    lazyQuotes: $options['lazy_quotes'],
                    trimLeadingSpace: $options['trim_leading_space']
                ),
            ],
            'factory' => [
                Csv::make($options),
            ],
        ];
    }

    public static function dataProviderDefaultOptions(): array
    {
        return [
            'constructed' => [new Csv],
            'factory' => [Csv::make()],
        ];
    }

    public static function dataProviderIgnoresUnexpectedKeys(): array
    {
        return [
            'constructed' => [new Csv(batchSize: 100, comment: '#')],
            'factory' => [Csv::make(['batch_size' => 100, 'comment' => '#'])],
        ];
    }

    #[Test, DataProvider('dataProviderCustomOptions')]
    public function it_custom_options(Csv $config): void
    {
        $this->assertEqualsCanonicalizing([
            'delimiter' => ';',
            'comment' => '#',
            'lazy_quotes' => true,
            'trim_leading_space' => true,
            'batch_size' => 100,
        ], $config->options());

        $this->assertSame(
            '{"delimiter":";","comment":"#","lazy_quotes":true,"trim_leading_space":true,"batch_size":100}',
            (string) $config
        );
    }

    #[Test, DataProvider('dataProviderDefaultOptions')]
    public function it_default_options(Csv $config): void
    {
        $this->assertEqualsCanonicalizing(
            ['delimiter' => ','],
            $config->options()
        );

        $this->assertSame(
            '{"delimiter":","}',
            (string) $config
        );
    }

    #[Test, DataProvider('dataProviderIgnoresUnexpectedKeys')]
    public function it_ignores_unexpected_keys(Csv $config): void
    {
        $this->assertEqualsCanonicalizing([
            'delimiter' => ',',
            'comment' => '#',
            'batch_size' => 100,
        ], $config->options());

        $this->assertSame(
            '{"delimiter":",","comment":"#","batch_size":100}',
            (string) $config
        );
    }
}
