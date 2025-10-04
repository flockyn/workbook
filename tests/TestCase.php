<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get the file path from the fixture's directory.
     */
    protected function fixture(string $name): string
    {
        $path = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.$name;

        $this->assertFalse(is_dir($path));
        $this->assertFileExists($path);

        return $path;
    }

    /**
     * Get the file paths from the fixture's directory.
     *
     * @return array<string>
     */
    protected function fixtures(string ...$names): array
    {
        return array_map([$this, 'fixture'], $names);
    }
}
