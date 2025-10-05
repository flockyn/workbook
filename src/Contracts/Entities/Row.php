<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Entities;

interface Row
{
    /**
     * Get the cells of the row.
     *
     * @return array<int, mixed>
     */
    public function getCells(): array;

    /**
     * Get the index of the row.
     */
    public function getIndex(): int;
}
