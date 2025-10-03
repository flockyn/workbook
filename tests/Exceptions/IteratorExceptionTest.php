<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Flockyn\Workbook\Exceptions\IteratorException;
use Flockyn\Workbook\Exceptions\WorkbookException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class IteratorExceptionTest extends TestCase
{
    #[Test]
    public function it_no_current_item(): void
    {
        $e = IteratorException::noCurrentItem();

        $this->assertInstanceOf(IteratorException::class, $e);
        $this->assertInstanceOf(WorkbookException::class, $e);

        $this->assertSame('The current item is not available.', $e->getMessage());
    }
}
