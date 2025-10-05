<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Iterators;

use Flockyn\Workbook\Contracts\Entities\Batch;
use Flockyn\Workbook\Contracts\Factories\BatchFactory;
use Flockyn\Workbook\Contracts\Iterators\BatchIterator as BatchIteratorInterface;

final class BatchIterator implements BatchIteratorInterface
{
    /**
     * The current batch.
     */
    private ?Batch $currentBatch = null;

    /**
     * The current position.
     */
    private int $position = 0;

    /**
     * Create a new class instance.
     */
    public function __construct(private readonly BatchFactory $factory)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function current(): ?Batch
    {
        return $this->currentBatch;
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function next(): void
    {
        $this->currentBatch = $this->factory->next();

        if ($this->valid()) {
            $this->position++;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->position = 0;
        $this->next();
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->currentBatch instanceof Batch;
    }
}
