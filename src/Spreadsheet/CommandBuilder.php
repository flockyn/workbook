<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Spreadsheet;

use Flockyn\Workbook\Config\Config;
use Flockyn\Workbook\Exceptions\IOException;

final class CommandBuilder
{
    /**
     * The action to perform on the binary.
     */
    private ?string $action = null;

    /**
     * Whether to use the stream mode.
     */
    private bool $useStream = false;

    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly ?Config $config = null,
        private readonly BinaryFinder $binary = new BinaryFinder
    ) {}

    /**
     * Build the command to execute.
     *
     * @param  non-empty-string  $path
     * @return array<int, string>
     */
    public function build(string $path): array
    {
        if (is_null($this->action)) {
            throw new IOException('No action has been set.');
        }

        if (! file_exists($path) || ! is_readable($path)) {
            throw new IOException("The file {$path} does not exist or is not readable!");
        }

        $config = $this->config instanceof Config ? explode(' ', "-c {$this->config}") : [];

        return array_values(array_filter([
            $this->binary->resolve(),
            $this->action,
            $path,
            $this->useStream ? '--stream' : null,
            ...$config,
        ]));
    }

    /**
     * Build the command to export the spreadsheet.
     *
     * @param  non-empty-string  $path
     * @return array<int, string>
     */
    public function buildExport(string $path): array
    {
        return $this->export()->build($path);
    }

    /**
     * Build the command to import the spreadsheet.
     *
     * @param  non-empty-string  $path
     * @return array<int, string>
     */
    public function buildImport(string $path, bool $stream = false): array
    {
        return $this->import()->stream($stream)->build($path);
    }

    /**
     * Set the action to export the spreadsheet.
     */
    public function export(): self
    {
        $this->action = 'export';

        return $this;
    }

    /**
     * Set the action to import the spreadsheet.
     */
    public function import(): self
    {
        $this->action = 'import';

        return $this;
    }

    /**
     * Delegate the stream mode to the binary.
     */
    public function stream(bool $enabled = true): self
    {
        $this->useStream = $enabled;

        return $this;
    }
}
