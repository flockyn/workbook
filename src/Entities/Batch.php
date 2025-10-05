<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Entities;

use Flockyn\Workbook\Contracts\Entities\Batch as BatchInterface;
use Flockyn\Workbook\Contracts\Factories\RowFactory;
use Flockyn\Workbook\Contracts\Iterators\RowIterator as RowIteratorInterface;
use Flockyn\Workbook\Iterators\RowIterator;

final readonly class Batch implements BatchInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(private int $rowStart, private int $rowEnd, private RowFactory $factory)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getRowEnd(): int
    {
        return $this->rowEnd;
    }

    /**
     * {@inheritDoc}
     */
    public function getRowIterator(): RowIteratorInterface
    {
        return new RowIterator($this->factory);
    }

    /**
     * {@inheritDoc}
     */
    public function getRowStart(): int
    {
        return $this->rowStart;
    }
}
