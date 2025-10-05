<?php

declare(strict_types=1);

namespace Tests\Entities;

use Flockyn\Workbook\Contracts\Entities\Batch;
use Flockyn\Workbook\Contracts\Entities\Row;
use Flockyn\Workbook\Contracts\Factories\BatchFactory;
use Flockyn\Workbook\Contracts\Iterators\BatchIterator;
use Flockyn\Workbook\Contracts\Iterators\RowIterator;
use Flockyn\Workbook\Entities\Sheet;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class SheetTest extends TestCase
{
    #[Test]
    public function it_can_iterate_batches(): void
    {
        $batchOne = $this->createMock(Batch::class);
        $batchTwo = $this->createMock(Batch::class);

        $factory = $this->createMock(BatchFactory::class);
        $factory->expects($this->exactly(3))
            ->method('next')
            ->willReturnOnConsecutiveCalls($batchOne, $batchTwo, null);

        $sheet = new Sheet(1, 'Sheet 1', $factory);

        $batches = [];
        foreach ($sheet->getBatchIterator() as $batch) {
            $batches[] = $batch;
        }

        $this->assertCount(2, $batches);
        $this->assertEquals([$batchOne, $batchTwo], $batches);
    }

    #[Test]
    public function it_can_iterate_over_rows_from_batches(): void
    {
        $mockOne = $this->createMock(Row::class);
        $mockTwo = $this->createMock(Row::class);

        $rowIterator = $this->createMock(RowIterator::class);
        $rowIterator->expects($this->exactly(2))
            ->method('next')
            ->willReturnOnConsecutiveCalls($mockOne, $mockTwo);
        $rowIterator->expects($this->exactly(2))
            ->method('current')
            ->willReturnOnConsecutiveCalls($mockOne, $mockTwo);
        $rowIterator->expects($this->exactly(3))
            ->method('valid')
            ->willReturnOnConsecutiveCalls(true, true, false);

        $batch = $this->createMock(Batch::class);
        $batch->expects($this->once())
            ->method('getRowIterator')
            ->willReturn($rowIterator);

        $factory = $this->createMock(BatchFactory::class);
        $factory->expects($this->exactly(2))
            ->method('next')
            ->willReturnOnConsecutiveCalls($batch, null);

        $sheet = new Sheet(1, 'Sheet 1', $factory);

        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rows[] = $row;
        }

        $this->assertCount(2, $rows);
        $this->assertEquals([$mockOne, $mockTwo], $rows);
    }

    #[Test]
    public function it_created_sheet_properly(): void
    {
        $factory = $this->createMock(BatchFactory::class);

        $sheet = new Sheet(1, 'Sheet 1', $factory);

        $this->assertEquals(1, $sheet->getIndex());
        $this->assertEquals('Sheet 1', $sheet->getName());
        $this->assertSame($factory, $sheet->getBatchFactory());
        $this->assertInstanceOf(BatchIterator::class, $sheet->getBatchIterator());
        $this->assertInstanceOf(RowIterator::class, $sheet->getRowIterator());
    }

    #[Test]
    public function it_allows_rewindable_iteration_through_batches(): void
    {
        $batchOne = $this->createMock(Batch::class);
        $batchTwo = $this->createMock(Batch::class);

        $factory = $this->createMock(BatchFactory::class);
        $factory->method('next')
            ->willReturnOnConsecutiveCalls($batchOne, $batchTwo, null, null);

        $sheet = new Sheet(1, 'Sheet 1', $factory);

        $batches = [];
        foreach ($sheet->getBatchIterator() as $batch) {
            $batches[] = $batch;
            break;
        }

        foreach ($sheet->getBatchIterator() as $batch) {
            $batches[] = $batch;
            break;
        }

        $this->assertCount(2, $batches);
        $this->assertEquals([$batchOne, $batchTwo], $batches);
    }

    #[Test]
    public function it_allows_rewindable_iteration_through_rows(): void
    {
        $rowOne = $this->createMock(Row::class);
        $rowTwo = $this->createMock(Row::class);

        $rowIterator = $this->createMock(RowIterator::class);
        $rowIterator->method('valid')
            ->willReturnOnConsecutiveCalls(true, true, false, true, true, false);
        $rowIterator->method('current')
            ->willReturnOnConsecutiveCalls($rowOne, $rowTwo, null, $rowOne, $rowTwo, null);
        $rowIterator->method('next')
            ->willReturnOnConsecutiveCalls($rowOne, $rowTwo, null, $rowOne, $rowTwo, null);

        $batch = $this->createMock(Batch::class);
        $batch->method('getRowIterator')->willReturn($rowIterator);

        $factory = $this->createMock(BatchFactory::class);
        $factory->method('next')
            ->willReturnOnConsecutiveCalls($batch, null, $batch, null);

        $sheet = new Sheet(1, 'Sheet 1', $factory);

        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rows[] = $row;
            break;
        }

        foreach ($sheet->getRowIterator() as $row) {
            $rows[] = $row;
            break;
        }

        $this->assertCount(2, $rows);
        $this->assertEquals([$rowOne, $rowTwo], $rows);
    }
}
