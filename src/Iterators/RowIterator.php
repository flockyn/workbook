<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Iterators;

use Flockyn\Workbook\Contracts\Entities\Row;
use Flockyn\Workbook\Contracts\Factories\RowFactory;
use Flockyn\Workbook\Contracts\Iterators\RowIterator as RowIteratorInterface;
use Generator;

final class RowIterator implements RowIteratorInterface
{
    /**
     * The list of rows.
     *
     * @var Generator<int, Row>
     */
    private readonly Generator $rows;

    /**
     * The current row.
     */
    private ?Row $currentRow = null;

    /**
     * Indicates if the iterator has been initialized.
     */
    private bool $initialized = false;

    /**
     * Create a new class instance.
     */
    public function __construct(RowFactory $factory)
    {
        $this->rows = $factory->rows();
    }

    /**
     * {@inheritDoc}
     */
    public function current(): ?Row
    {
        return $this->currentRow;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return $this->currentRow?->getIndex() ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        if (! $this->initialized) {
            $this->rewind();

            return;
        }

        $this->rows->next();
        $this->refreshCurrentRow();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;
        $this->rows->rewind();
        $this->refreshCurrentRow();
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->currentRow instanceof Row;
    }

    /**
     * Refresh the current row.
     */
    private function refreshCurrentRow(): void
    {
        $this->currentRow = null;

        if ($this->rows->valid()) {
            $this->currentRow = $this->rows->current();
        }
    }
}
