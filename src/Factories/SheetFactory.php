<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Factories;

use Flockyn\Workbook\Contracts\Entities\Sheet as SheetInterface;
use Flockyn\Workbook\Contracts\Factories\SheetFactory as SheetFactoryInterface;
use Flockyn\Workbook\Entities\Sheet;
use Flockyn\Workbook\Exceptions\FactoryException;
use Flockyn\Workbook\Exceptions\IOException;
use Generator;
use JsonException;
use Symfony\Component\Process\Process;

final class SheetFactory implements SheetFactoryInterface
{
    /**
     * The list of sheets.
     *
     * @var SheetInterface[]
     */
    private array $sheets = [];

    /**
     * Create a new class instance.
     *
     * @param  Generator<string, string>  $stdout
     */
    public function __construct(Generator $stdout)
    {
        $this->parseOutput($stdout);
    }

    /**
     * {@inheritDoc}
     */
    public static function make(array $data): SheetInterface
    {
        $index = $data['index'] ?? null;
        if (! is_int($index)) {
            throw FactoryException::invalidField('sheet', 'index', 'integer');
        }

        $name = $data['name'] ?? null;
        if (! is_string($name)) {
            throw FactoryException::invalidField('sheet', 'name', 'string');
        }

        return new Sheet($index, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function sheets(): array
    {
        return $this->sheets;
    }

    /**
     * Process a single sheet entry and append its batch.
     *
     * @param  array<string, mixed>  $entry
     *
     * @throws FactoryException
     */
    private function initializeSheet(array $entry): void
    {
        $index = $entry['index'] ?? null;
        if (! is_int($index)) {
            throw FactoryException::invalidField('sheet', 'index', 'integer');
        }

        $batch = $entry['batch'] ?? null;
        if (! is_array($batch)) {
            throw FactoryException::invalidField('sheet', 'batch', 'array');
        }

        $sheet = $this->sheets[$index] ??= self::make($entry);

        /** @var array<string, mixed> $batch */
        $sheet->getBatchFactory()->append($batch);
    }

    /**
     * Extract full lines from the buffer and process them.
     */
    private function parseLinesFromBuffer(string $buffer): string
    {
        while (($pos = mb_strpos($buffer, "\n")) !== false) {
            $line = mb_substr($buffer, 0, $pos);
            $buffer = mb_substr($buffer, $pos + 1);

            if ($line === '') {
                continue;
            }

            $this->parseSheetLine($line);
        }

        return $buffer;
    }

    /**
     * Consume process output and parse sheets.
     *
     * @param  Generator<string, string>  $stdout
     */
    private function parseOutput(Generator $stdout): void
    {
        $buffer = '';

        foreach ($stdout as $type => $data) {
            if ($type === Process::ERR) {
                if (preg_match('/Error:\s*(.*?)(\nUsage:|$)/s', $data, $matches) !== false) {
                    $data = ucfirst($matches[1] ?? $data);
                }

                throw new IOException($data);
            }

            $buffer .= $data;
            $buffer = $this->parseLinesFromBuffer($buffer);
        }
    }

    /**
     * Decode and process a single JSON line.
     *
     * @throws IOException
     */
    private function parseSheetLine(string $line): void
    {
        try {
            $decoded = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new IOException('Failed to decode JSON: '.$e->getMessage().'.', $e->getCode(), $e);
        }

        if (! is_array($decoded) || ! isset($decoded['sheets']) || ! is_array($decoded['sheets'])) {
            throw new IOException('Invalid sheet data received from the process.');
        }

        foreach ($decoded['sheets'] as $entry) {
            /** @var array<string, mixed> $entry */
            $this->initializeSheet($entry);
        }
    }
}
