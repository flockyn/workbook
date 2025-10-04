<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Config;

use Flockyn\PHPFlock\Arr;
use JsonException;
use Stringable;

abstract class Config implements Stringable
{
    /**
     * Get the configuration options.
     *
     * @return array<string, mixed>
     */
    abstract public function options(): array;

    /**
     * Create a new configuration instance.
     *
     * @param  array<string, mixed>  $options
     */
    final public static function make(array $options = []): static
    {
        return new static(...Arr::toCamelKeys($options, 1)); // @phpstan-ignore new.static
    }

    /**
     * {@inheritDoc}
     *
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode((object) $this->options(), JSON_THROW_ON_ERROR);
    }
}
