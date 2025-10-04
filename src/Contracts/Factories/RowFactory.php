<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Factories;

use Flockyn\Workbook\Contracts\Entities\Row;
use Generator;

interface RowFactory
{
    /**
     * Create a row from array data.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \Flockyn\Workbook\Exceptions\FactoryException
     */
    public static function make(array $data): Row;

    /**
     * Create rows from array data.
     *
     * @return Generator<int, Row>
     */
    public function rows(): Generator;
}
