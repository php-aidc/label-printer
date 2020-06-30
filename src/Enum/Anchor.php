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
 * @method static Anchor NORTHWEST() 1
 * @method static Anchor NORTH() 2
 * @method static Anchor NORTHEAST() 3
 * @method static Anchor WEST() 4
 * @method static Anchor CENTER() 5
 * @method static Anchor EAST() 6
 * @method static Anchor SOUTHWEST() 7
 * @method static Anchor SOUTH() 8
 * @method static Anchor SOUTHEAST() 9
 */
final class Anchor extends Enum
{
    private const NORTHWEST = 1;
    private const NORTH = 2;
    private const NORTHEAST = 3;
    private const WEST = 4;
    private const CENTER = 5;
    private const EAST = 6;
    private const SOUTHWEST = 7;
    private const SOUTH = 8;
    private const SOUTHEAST = 9;
}
