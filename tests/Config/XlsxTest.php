<?php

declare(strict_types=1);

namespace Tests\Config;

use Flockyn\Workbook\Config\Xlsx;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class XlsxTest extends TestCase
{
    public static function dataProviderCustomOptions(): array
    {
        return [
            'constructed' => [new Xlsx(batchSize: 100, password: 'secret')],
            'factory' => [Xlsx::make(['batch_size' => 100, 'password' => 'secret'])],
        ];
    }

    public static function dataProviderDefaultOptions(): array
    {
        return [
            'constructed' => [new Xlsx],
            'factory' => [Xlsx::make()],
        ];
    }

    public static function dataProviderIgnoresUnexpectedKeys(): array
    {
        return [
            'constructed' => [new Xlsx(batchSize: 100)],
            'factory' => [Xlsx::make(['batch_size' => 100])],
        ];
    }

    #[Test, DataProvider('dataProviderCustomOptions')]
    public function it_custom_options(Xlsx $config): void
    {
        $this->assertEqualsCanonicalizing([
            'batch_size' => 100,
            'password' => 'secret',
        ], $config->options());

        $this->assertSame(
            '{"batch_size":100,"password":"secret"}',
            (string) $config
        );
    }

    #[Test, DataProvider('dataProviderDefaultOptions')]
    public function it_default_options(Xlsx $config): void
    {
        $this->assertEqualsCanonicalizing(
            [], $config->options()
        );

        $this->assertSame(
            '{}', (string) $config
        );
    }

    #[Test, DataProvider('dataProviderIgnoresUnexpectedKeys')]
    public function it_ignores_unexpected_keys(Xlsx $config): void
    {
        $this->assertEqualsCanonicalizing(
            ['batch_size' => 100],
            $config->options()
        );

        $this->assertSame(
            '{"batch_size":100}',
            (string) $config
        );
    }
}
