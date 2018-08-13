<?php declare(strict_types=1);

namespace DCarbone\Go\HTTP;

/**
 * @param int $code
 * @return string
 */
function StatusText(int $code): string
{
    return StatusTexts[$code] ?? '';
}