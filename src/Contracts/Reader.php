<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts;

interface Reader
{
    /**
     * Close the reader.
     */
    public function close(): void;

    /**
     * Get the sheet iterator.
     */
    public function getSheetIterator(): Iterators\SheetIterator;

    /**
     * Open the reader for the given path.
     *
     * @param  non-empty-string  $path
     */
    public function open(string $path): void;
}
