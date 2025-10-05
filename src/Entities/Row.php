<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Entities;

use Flockyn\Workbook\Contracts\Entities\Row as RowInterface;

final readonly class Row implements RowInterface
{
    /**
     * Create a new class instance.
     *
     * @param  array<int, mixed>  $cells
     */
    public function __construct(private int $index, private array $cells)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * {@inheritDoc}
     */
    public function getCells(): array
    {
        return $this->cells;
    }
}
