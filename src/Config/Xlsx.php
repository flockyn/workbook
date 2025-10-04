<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Config;

final class Xlsx extends Config
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly ?int $batchSize = null,
        private readonly ?string $password = null
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function options(): array
    {
        return array_filter([
            'batch_size' => $this->batchSize,
            'password' => $this->password,
        ], fn (mixed $value): bool => $value !== null);
    }
}
