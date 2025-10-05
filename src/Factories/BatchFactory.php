<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Factories;

use Flockyn\Workbook\Contracts\Entities\Batch as BatchInterface;
use Flockyn\Workbook\Contracts\Factories\BatchFactory as BatchFactoryInterface;
use Flockyn\Workbook\Entities\Batch;
use Flockyn\Workbook\Exceptions\FactoryException;
use SplDoublyLinkedList;
use SplQueue;

final readonly class BatchFactory implements BatchFactoryInterface
{
    /**
     * The queue of batches.
     *
     * @var SplQueue<BatchInterface>
     */
    private SplQueue $batches;

    /**
     * Create a new class instance.
     *
     * @param  array<int, array<string, mixed>>  $batches
     */
    public function __construct(array $batches = [])
    {
        $this->batches = new SplQueue;
        $this->batches->setIteratorMode(
            SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP
        );

        foreach ($batches as $batch) {
            $this->append($batch);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function make(array $batch): BatchInterface
    {
        $batch['row_start'] ??= null;
        $batch['row_end'] ??= null;

        if (! is_int($batch['row_start']) || ! is_int($batch['row_end'])) {
            throw FactoryException::invalidField('batch', 'row_start|row_end', 'integers');
        }

        $batch['rows'] ??= null;
        if (! is_array($batch['rows']) && empty($batch['rows'])) {
            throw FactoryException::invalidField('batch', 'rows', 'array');
        }

        return new Batch($batch['row_start'], $batch['row_end'], new RowFactory($batch['rows'])); // @phpstan-ignore argument.type
    }

    /**
     * {@inheritDoc}
     */
    public function append(array $batch): void
    {
        $this->batches->enqueue(self::make($batch));
    }

    /**
     * {@inheritDoc}
     */
    public function next(): ?BatchInterface
    {
        return $this->batches->isEmpty() ? null : $this->batches->dequeue();
    }
}
