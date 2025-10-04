<?php

declare(strict_types=1);

namespace Flockyn\Workbook\Contracts\Iterators;

use Iterator;

/**
 * @extends Iterator<int, \Flockyn\Workbook\Contracts\Entities\Batch|null>
 */
interface BatchIterator extends Iterator {}
