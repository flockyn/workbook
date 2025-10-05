<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Iterators;

use Flockyn\Workbook\Contracts\Entities\Sheet;
use Flockyn\Workbook\Contracts\Factories\SheetFactory;
use Flockyn\Workbook\Contracts\Iterators\SheetIterator as SheetIteratorInterface;

final class SheetIterator implements SheetIteratorInterface
{
    /**
     * The list of sheets.
     *
     * @var Sheet[]
     */
    private array $sheets;

    /**
     * The current position.
     */
    private int $position = 0;

    /**
     * Create a new class instance.
     */
    public function __construct(SheetFactory $factory)
    {
        $this->sheets = $factory->sheets();
    }

    /**
     * {@inheritDoc}
     */
    public function current(): Sheet
    {
        return $this->sheets[$this->position];
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return $this->current()->getIndex();
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return isset($this->sheets[$this->position]);
    }
}
