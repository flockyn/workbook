<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Entities;

use Flockyn\Workbook\Contracts\Entities\Batch as BatchInterface;
use Flockyn\Workbook\Contracts\Entities\Row as RowInterface;
use Flockyn\Workbook\Contracts\Entities\Sheet as SheetInterface;
use Flockyn\Workbook\Contracts\Factories\BatchFactory as BatchFactoryInterface;
use Flockyn\Workbook\Contracts\Iterators\BatchIterator as BatchIteratorInterface;
use Flockyn\Workbook\Contracts\Iterators\RowIterator as RowIteratorInterface;
use Flockyn\Workbook\Factories\BatchFactory;
use Flockyn\Workbook\Factories\RowFactory;
use Flockyn\Workbook\Iterators\BatchIterator;
use Flockyn\Workbook\Iterators\RowIterator;
use Generator;

final class Sheet implements SheetInterface
{
    /**
     * The instance of row iterator.
     */
    private ?RowIteratorInterface $rowIterator = null;

    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly int $index,
        private readonly string $name,
        private readonly BatchFactoryInterface $factory = new BatchFactory,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getBatchFactory(): BatchFactoryInterface
    {
        return $this->factory;
    }

    /**
     * {@inheritDoc}
     */
    public function getBatchIterator(): BatchIteratorInterface
    {
        return new BatchIterator($this->factory);
    }

    /**
     * {@inheritDoc}
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getRowIterator(): RowIteratorInterface
    {
        $this->rowIterator ??= new RowIterator(new RowFactory($this->createRows()));
        $this->rowIterator->next();

        return $this->rowIterator;
    }

    /**
     * Create rows from batches.
     *
     * @return Generator<int, RowInterface>
     */
    private function createRows(): Generator
    {
        foreach ($this->getBatchIterator() as $batch) {
            if (! $batch instanceof BatchInterface) {
                continue;
            }

            foreach ($batch->getRowIterator() as $row) {
                if ($row instanceof RowInterface) {
                    yield $row;
                }
            }
        }
    }
}
