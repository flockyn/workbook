<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Factories;

use Flockyn\Workbook\Contracts\Entities\Sheet;

interface SheetFactory
{
    /**
     * Create a sheet from array data.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \Flockyn\Workbook\Exceptions\FactoryException
     */
    public static function make(array $data): Sheet;

    /**
     * Get all sheets.
     *
     * @return Sheet[]
     */
    public function sheets(): array;
}
