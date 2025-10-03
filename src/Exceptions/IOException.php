<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Exceptions;

use RuntimeException;

final class IOException extends RuntimeException implements WorkbookException {}
