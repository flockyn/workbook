<?php

declare(strict_types=1);

namespace Tests\DataProvider;

final class ReaderDataProvider
{
    public static function csv_configurable(): array
    {
        return [
            'delimiter comma' => ['csv/standard.csv', ['delimiter' => ',']],
            'delimiter pipeline' => ['csv/with_delimiter_pipeline.csv', ['delimiter' => '|']],
            'delimiter semicolon' => ['csv/with_delimiter_semicolon.csv', ['delimiter' => ';']],
            'trim leading space' => ['csv/with_trim_leading_space.csv', ['trim_leading_space' => true]],
            'comment' => ['csv/with_comment.csv', ['comment' => '#']],
            'multiple empty lines' => ['csv/with_multiple_empty_lines.csv', []],
            'lazy quotes' => ['csv/with_lazy_quotes.csv', ['lazy_quotes' => true], [
                ['cell--11', 'cell--12 with "a quote"', 'cell--13'],
                ['cell--21 with a "non-standard" quote', 'cell--22', 'cell--23'],
                ['cell--31', 'cell--32, with a comma', 'cell--33'],
            ]],
        ];
    }

    public static function empty_files(): array
    {
        return [
            'csv' => ['csv/empty.csv'],
        ];
    }

    public static function standard_files(): array
    {
        return [
            'csv' => ['csv/standard.csv'],
        ];
    }
}
