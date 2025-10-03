<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Exceptions;

use RuntimeException;

final class FactoryException extends RuntimeException implements WorkbookException
{
    /**
     * Create a new instance when the field is invalid.
     *
     *      FactoryException::invalidField('batch', 'row_start|row_end', 'integers');
     *      FactoryException::invalidField('row', 'index', 'integer');
     *      FactoryException::invalidField('sheet', 'name', 'string');
     */
    public static function invalidField(string $entity, string $field, string $expected): self
    {
        $fields = array_map('trim', explode('|', $field));
        $fields = array_map(fn ($item): string => "\"{$item}\"", $fields);

        $length = count($fields);
        $prefix = $length > 1 ? 'valid' : 'a valid';

        $field = match ($length) {
            1 => $fields[0],
            2 => implode(' and ', $fields),
            default => (static function (array $items): string {
                /** @var string $last */
                $last = array_pop($items);
                $rest = implode(', ', $items);

                return "{$rest}, and {$last}";
            })($fields),
        };

        return new self("The {$entity} must have {$prefix} {$field} of type \"{$expected}\".");
    }
}
