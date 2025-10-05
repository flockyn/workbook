<?php

declare(strict_types=1);

namespace Tests\Entities;

use Flockyn\Workbook\Contracts\Entities\Row;
use Flockyn\Workbook\Contracts\Factories\RowFactory;
use Flockyn\Workbook\Contracts\Iterators\RowIterator;
use Flockyn\Workbook\Entities\Batch;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class BatchTest extends TestCase
{
    #[Test]
    public function it_created_batch_properly(): void
    {
        $factory = $this->createMock(RowFactory::class);

        $batch = new Batch(1, 10, $factory);

        $this->assertEquals(1, $batch->getRowStart());
        $this->assertEquals(10, $batch->getRowEnd());
        $this->assertInstanceOf(RowIterator::class, $batch->getRowIterator());
    }

    #[Test]
    public function it_allows_iteration_over_factory_rows(): void
    {
        $rowOne = $this->createMock(Row::class);
        $rowTwo = $this->createMock(Row::class);

        $factory = $this->createMock(RowFactory::class);
        $factory->expects($this->once())
            ->method('rows')
            ->willReturn((function () use ($rowOne, $rowTwo) {
                yield $rowOne;
                yield $rowTwo;
            })());

        $batch = new Batch(0, 1, $factory);

        $rows = [];
        foreach ($batch->getRowIterator() as $row) {
            $rows[] = $row;
        }

        $this->assertCount(2, $rows);
        $this->assertEquals([$rowOne, $rowTwo], $rows);
    }
}
