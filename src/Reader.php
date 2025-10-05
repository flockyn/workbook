<?php

declare(strict_types=1);

namespace Flockyn\Workbook;

use Flockyn\Workbook\Config\Config;
use Flockyn\Workbook\Contracts\Iterators\SheetIterator as SheetIteratorInterface;
use Flockyn\Workbook\Contracts\Reader as ReaderInterface;
use Flockyn\Workbook\Exceptions\IOException;
use Flockyn\Workbook\Factories\SheetFactory;
use Flockyn\Workbook\Iterators\SheetIterator;
use Flockyn\Workbook\Spreadsheet\CommandBuilder;
use Symfony\Component\Process\Process;

final class Reader implements ReaderInterface
{
    /**
     * The command builder instance.
     */
    private readonly CommandBuilder $commandBuilder;

    /**
     * The sheet iterator instance.
     */
    private ?SheetIteratorInterface $sheetIterator = null;

    /**
     * The process instance.
     */
    private ?Process $process = null;

    /**
     * Create a new class instance.
     */
    public function __construct(private readonly ?Config $config = null)
    {
        $this->commandBuilder = new CommandBuilder(config: $this->config);
    }

    /**
     * {@inheritDoc}
     */
    public function close(): void
    {
        if ($this->isRunning()) {
            $this->process->stop();
        }

        $this->sheetIterator = null;
        $this->process = null;
    }

    /**
     * {@inheritDoc}
     */
    public function getSheetIterator(): SheetIteratorInterface
    {
        if (! $this->isInitialized()) {
            throw new IOException('The process is not running!');
        }

        return $this->sheetIterator;
    }

    /**
     * {@inheritDoc}
     */
    public function open(string $path): void
    {
        $command = $this->commandBuilder->buildImport($path, stream: true);

        $this->process = new Process($command, timeout: 0);
        $this->process->start();

        $this->sheetIterator = new SheetIterator(
            new SheetFactory($this->process->getIterator())
        );
    }

    /**
     * Determine if the reader is initialized.
     *
     * @phpstan-assert-if-true Process $this->process
     * @phpstan-assert-if-true SheetIteratorInterface $this->sheetIterator
     */
    private function isInitialized(): bool
    {
        return $this->process instanceof Process && $this->sheetIterator instanceof SheetIteratorInterface;
    }

    /**
     * Determine if the reader is running.
     *
     * @phpstan-assert-if-true Process $this->process
     */
    private function isRunning(): bool
    {
        return $this->isInitialized() && $this->process->isRunning();
    }
}
