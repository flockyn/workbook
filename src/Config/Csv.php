<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Config;

final class Csv extends Config
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly ?int $batchSize = null,
        private readonly string $delimiter = ',',
        private readonly ?string $comment = null,
        private readonly ?bool $lazyQuotes = null,
        private readonly ?bool $trimLeadingSpace = null,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function options(): array
    {
        return array_filter([
            'delimiter' => $this->delimiter,
            'comment' => $this->comment,
            'lazy_quotes' => $this->lazyQuotes,
            'trim_leading_space' => $this->trimLeadingSpace,
            'batch_size' => $this->batchSize,
        ], fn (mixed $value): bool => $value !== null);
    }
}
