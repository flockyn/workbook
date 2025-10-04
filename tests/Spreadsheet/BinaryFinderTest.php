<?php

declare(strict_types=1);

namespace Tests\Spreadsheet;

use Flockyn\Workbook\Exceptions\IOException;
use Flockyn\Workbook\Spreadsheet\BinaryFinder;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Process\ExecutableFinder;
use Tests\TestCase;

final class BinaryFinderTest extends TestCase
{
    #[Test]
    public function it_can_resolve_binary_file_from_vendor_bin(): void
    {
        $finder = new BinaryFinder;

        $binary = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'spreadsheet';

        $this->assertSame($binary, $finder->resolve());
    }

    #[Test]
    public function it_throws_an_exception_when_binary_not_found(): void
    {
        $executable = $this->createMock(ExecutableFinder::class);
        $executable->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(IOException::class);
        $this->expectExceptionMessage('The spreadsheet binary could not be found.');

        $finder = new BinaryFinder($executable);
        $finder->resolve();
    }
}
