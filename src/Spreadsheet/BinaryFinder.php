<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Spreadsheet;

use Flockyn\Workbook\Exceptions\IOException;
use Symfony\Component\Process\ExecutableFinder;

final readonly class BinaryFinder
{
    /**
     * The name of the spreadsheet binary.
     */
    private const FILENAME = 'spreadsheet';

    /**
     * Create a new class instance.
     */
    public function __construct(private ExecutableFinder $executable = new ExecutableFinder)
    {
        //
    }

    /**
     * Resolve the spreadsheet binary.
     *
     * @throws IOException
     */
    public function resolve(): string
    {
        return $this->executable->find(self::FILENAME, extraDirs: [
            dirname(__DIR__, 4).DIRECTORY_SEPARATOR.'bin',
            dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'bin',
        ]) ?? throw new IOException('The spreadsheet binary could not be found.');
    }
}
