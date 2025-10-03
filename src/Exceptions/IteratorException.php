<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Exceptions;

use RuntimeException;

final class IteratorException extends RuntimeException implements WorkbookException
{
    /**
     * Create a new instance when the current item is not available.
     */
    public static function noCurrentItem(): self
    {
        return new self('The current item is not available.');
    }
}
