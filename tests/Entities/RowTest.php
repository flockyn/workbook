<?php

declare(strict_types=1);

namespace Tests\Entities;

use Flockyn\Workbook\Entities\Row;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class RowTest extends TestCase
{
    #[Test]
    public function it_created_row_properly(): void
    {
        $row = new Row(1, ['foo', 'bar']);

        $this->assertEquals(1, $row->getIndex());
        $this->assertEqualsCanonicalizing(['foo', 'bar'], $row->getCells());
    }
}
