<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Entities;

use Flockyn\Workbook\Contracts\Iterators\RowIterator;

interface Batch
{
    /**
     * Get the row end batch.
     */
    public function getRowEnd(): int;

    /**
     * Get the row iterator.
     */
    public function getRowIterator(): RowIterator;

    /**
     * Get the row start batch.
     */
    public function getRowStart(): int;
}
