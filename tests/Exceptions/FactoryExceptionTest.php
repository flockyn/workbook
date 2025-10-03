<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Flockyn\Workbook\Exceptions\FactoryException;
use Flockyn\Workbook\Exceptions\WorkbookException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class FactoryExceptionTest extends TestCase
{
    public static function dataProviderInvalidField(): array
    {
        return [
            'single field' => [
                'row',
                'index',
                'integer',
                'The row must have a valid "index" of type "integer".',
            ],
            'two fields' => [
                'batch',
                'row_start|row_end',
                'integers',
                'The batch must have valid "row_start" and "row_end" of type "integers".',
            ],
            'multiple fields' => [
                'sheet',
                'name|title|label',
                'string',
                'The sheet must have valid "name", "title", and "label" of type "string".',
            ],
        ];
    }

    #[Test, DataProvider('dataProviderInvalidField')]
    public function it_invalid_field(string $entity, string $field, string $expected, string $message): void
    {
        $e = FactoryException::invalidField($entity, $field, $expected);

        $this->assertInstanceOf(FactoryException::class, $e);
        $this->assertInstanceOf(WorkbookException::class, $e);

        $this->assertSame($message, $e->getMessage());
    }
}
