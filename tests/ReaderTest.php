<?php

declare(strict_types=1);

namespace Tests;

use Flockyn\Workbook\Config\Config as WorkbookConfig;
use Flockyn\Workbook\Config\Csv;
use Flockyn\Workbook\Exceptions\IOException;
use Flockyn\Workbook\Reader;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use Tests\DataProvider\ReaderDataProvider;

final class ReaderTest extends TestCase
{
    private array $cells = [
        ['cell--11', 'cell--12', 'cell--13'],
        ['cell--21', 'cell--22', 'cell--23'],
        ['cell--31', 'cell--32', 'cell--33'],
    ];

    #[Test, DataProviderExternal(ReaderDataProvider::class, 'standard_files')]
    public function it_allows_rewindable_batch_iteration_across_sheets(string $file): void
    {
        $batches = [];

        $reader = $this->reader(Csv::make(['batch_size' => 1]));
        $reader->open($this->fixture($file));

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getBatchIterator() as $batch) {
                $batches[] = $batch->getRowStart().':'.$batch->getRowEnd();
                break;
            }

            foreach ($sheet->getBatchIterator() as $batch) {
                $batches[] = $batch->getRowStart().':'.$batch->getRowEnd();
                break;
            }
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getBatchIterator() as $batch) {
                $batches[] = $batch->getRowStart().':'.$batch->getRowEnd();
                break;
            }
        }

        $reader->close();

        $this->assertCount(3, $batches);
        $this->assertEqualsCanonicalizing(['0:0', '1:1', '2:2'], $batches);
    }

    #[Test, DataProviderExternal(ReaderDataProvider::class, 'standard_files')]
    public function it_allows_rewindable_row_iteration_across_sheets(string $file): void
    {
        $rows = [];

        $reader = $this->reader();
        $reader->open($this->fixture($file));

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->getCells();
                break;
            }

            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->getCells();
                break;
            }
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->getCells();
                break;
            }
        }

        $reader->close();

        $this->assertCount(3, $rows);
        $this->assertEqualsCanonicalizing($this->cells, $rows);
    }

    #[Test, DataProviderExternal(ReaderDataProvider::class, 'csv_configurable')]
    public function it_correct_rows_from_csv(string $file, array $values, array $expected = []): void
    {
        $rows = $this->rows($file, Csv::make($values));

        $this->assertEqualsCanonicalizing($expected ?: $this->cells, $rows);
    }

    #[Test, DataProviderExternal(ReaderDataProvider::class, 'empty_files')]
    public function it_correct_rows_from_empty_files(string $file): void
    {
        $rows = $this->rows($file);

        $this->assertEmpty($rows);
    }

    #[Test]
    public function it_get_sheet_iterator_should_throw_an_exception_before_opened(): void
    {
        $this->expectException(IOException::class);
        $this->expectExceptionMessage('The process is not running!');

        $this->reader()->getSheetIterator();
    }

    /**
     * Create a new instance of the Reader class.
     */
    private function reader(?WorkbookConfig $config = null): Reader
    {
        return new Reader($config);
    }

    /**
     * Get the rows from the file.
     */
    private function rows(string $file, ?WorkbookConfig $config = null): array
    {
        $reader = $this->reader($config);
        $reader->open($this->fixture($file));

        $rows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->getCells();
            }
        }

        $reader->close();

        return $rows;
    }
}
