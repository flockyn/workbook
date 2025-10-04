<?php

declare(strict_types=1);

namespace Tests\Spreadsheet;

use Flockyn\Workbook\Config\Config;
use Flockyn\Workbook\Exceptions\IOException;
use Flockyn\Workbook\Spreadsheet\CommandBuilder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class CommandBuilderTest extends TestCase
{
    private string $binary;

    private string $temp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->binary = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'spreadsheet';

        $this->temp = tempnam(sys_get_temp_dir(), 'spreadsheet').'.csv';
        file_put_contents($this->temp, 'foo,bar');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unlink($this->temp);
    }

    #[Test]
    public function it_allowed_fluent(): void
    {
        $command = new CommandBuilder;

        $command->export();
        $this->assertSame('export', $this->getProperty($command, 'action'));

        $command->import();
        $this->assertSame('import', $this->getProperty($command, 'action'));

        $command->stream();
        $this->assertTrue($this->getProperty($command, 'useStream'));

        $command->stream(false);
        $this->assertFalse($this->getProperty($command, 'useStream'));
    }

    #[Test]
    public function it_build_export_command(): void
    {
        $command = new CommandBuilder;

        $this->assertEqualsCanonicalizing([
            $this->binary,
            'export',
            $this->temp,
        ], $command->buildExport($this->temp));
    }

    #[Test]
    public function it_build_import_command(): void
    {
        $command = new CommandBuilder;

        $this->assertEqualsCanonicalizing([
            $this->binary,
            'import',
            $this->temp,
        ], $command->buildImport($this->temp));
    }

    #[Test]
    public function it_build_import_command_with_stream(): void
    {
        $command = new CommandBuilder;

        $this->assertEqualsCanonicalizing([
            $this->binary,
            'import',
            $this->temp,
            '--stream',
        ], $command->buildImport($this->temp, true));
    }

    #[Test]
    public function it_build_with_config(): void
    {
        $command = new CommandBuilder(new class extends Config
        {
            public function options(): array
            {
                return ['delimiter' => ',', 'batch_size' => 100];
            }
        });

        $this->assertEqualsCanonicalizing([
            $this->binary,
            'import',
            $this->temp,
            '-c',
            '{"delimiter":",","batch_size":100}',
        ], $command->buildImport($this->temp));
    }

    #[Test]
    public function it_throws_when_action_not_set(): void
    {
        $command = new CommandBuilder;

        $this->expectException(IOException::class);
        $this->expectExceptionMessage('No action has been set.');

        $command->build($this->temp);
    }

    #[Test]
    public function it_throws_when_file_not_exists(): void
    {
        $command = new CommandBuilder;

        $this->expectException(IOException::class);
        $this->expectExceptionMessage('The file /path/to/file.csv does not exist or is not readable!');

        $command->import()->build('/path/to/file.csv');
    }

    private function getProperty(CommandBuilder $command, string $property): mixed
    {
        return (fn () => $this->{$property})->call($command);
    }
}
