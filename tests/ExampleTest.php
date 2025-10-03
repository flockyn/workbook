<?php

declare(strict_types=1);

namespace Tests;

use Flockyn\Workbook\Example;
use PHPUnit\Framework\Attributes\Test;

final class ExampleTest extends TestCase
{
    #[Test]
    public function it_should_run(): void
    {
        $this->assertSame(0, (new Example)->run());
    }
}
