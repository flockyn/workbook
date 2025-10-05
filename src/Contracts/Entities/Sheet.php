<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Entities;

use Flockyn\Workbook\Contracts\Factories\BatchFactory;
use Flockyn\Workbook\Contracts\Iterators\BatchIterator;
use Flockyn\Workbook\Contracts\Iterators\RowIterator;

interface Sheet
{
    /**
     * Get the batch factory.
     */
    public function getBatchFactory(): BatchFactory;

    /**
     * Get the batch iterator.
     */
    public function getBatchIterator(): BatchIterator;

    /**
     * Get the index of the sheet.
     */
    public function getIndex(): int;

    /**
     * Get the name of the sheet.
     */
    public function getName(): string;

    /**
     * Get the row iterator.
     */
    public function getRowIterator(): RowIterator;
}
