<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 * © Appwilio (https://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @noinspection PhpUnusedPrivateFieldInspection */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static FORWARD()
 * @method static BACKWARD()
 */
final class Direction extends Enum
{
    private const FORWARD = 0;
    private const BACKWARD = 1;
}
