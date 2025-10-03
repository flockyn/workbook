<?php

declare(strict_types=1);

namespace Tests;

use Flockyn\Workbook\Example;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    #[Test]
    public function it_should_run(): void
    {
        $this->assertSame(0, (new Example)->run());
    }
}
