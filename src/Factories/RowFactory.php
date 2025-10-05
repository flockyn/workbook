<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Factories;

use Closure;
use Flockyn\Workbook\Contracts\Entities\Row as RowInterface;
use Flockyn\Workbook\Contracts\Factories\RowFactory as RowFactoryInterface;
use Flockyn\Workbook\Entities\Row;
use Flockyn\Workbook\Exceptions\FactoryException;
use Generator;

final readonly class RowFactory implements RowFactoryInterface
{
    /**
     * The list of rows.
     *
     * @var Generator<int, RowInterface>
     */
    private Generator $rows;

    /**
     * Create a new class instance.
     *
     * @template TRowValues of array<string, mixed>|RowInterface
     * @template TRow of iterable<int, TRowValues>
     *
     * @param  (Closure(): TRow)|TRow  $rows
     */
    public function __construct(Closure|iterable $rows = [])
    {
        $this->rows = $this->initialize($rows instanceof Closure ? $rows() : $rows);
    }

    /**
     * {@inheritDoc}
     */
    public static function make(array $data): RowInterface
    {
        $index = $data['index'] ?? null;
        if (! is_int($index)) {
            throw FactoryException::invalidField('row', 'index', 'integer');
        }

        $cells = $data['cells'] ?? null;
        if (! is_array($cells)) {
            throw FactoryException::invalidField('row', 'cells', 'array');
        }

        if (! array_is_list($cells)) {
            throw FactoryException::invalidField('row', 'cells', 'array with numeric keys');
        }

        return new Row($index, $cells);
    }

    /**
     * {@inheritDoc}
     */
    public function rows(): Generator
    {
        return $this->rows;
    }

    /**
     * Initialize the rows.
     *
     * @template TRowValues of array<string, mixed>|RowInterface
     * @template TRow of iterable<int, TRowValues>
     *
     * @param  TRow  $rows
     * @return Generator<int, RowInterface>
     */
    private function initialize(iterable $rows): Generator
    {
        foreach ($rows as $row) {
            yield $row instanceof RowInterface ? $row : self::make($row);
        }
    }
}
