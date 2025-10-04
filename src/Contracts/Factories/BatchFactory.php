<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Factories;

use Flockyn\Workbook\Contracts\Entities\Batch;

interface BatchFactory
{
    /**
     * Make a new batch from the given array.
     *
     * @param  array<string, mixed>  $batch
     *
     * @throws \Flockyn\Workbook\Exceptions\FactoryException
     */
    public static function make(array $batch): Batch;

    /**
     * Append a new batch to the queue.
     *
     * @param  array<string, mixed>  $batch
     */
    public function append(array $batch): void;

    /**
     * Get the next batch from the queue.
     */
    public function next(): ?Batch;
}
