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
 * @method static Angle _0()
 * @method static Angle _90()
 * @method static Angle _180()
 * @method static Angle _270()
 */
final class Angle extends Enum
{
    private const _0 = 0;
    private const _90 = 1;
    private const _180 = 2;
    private const _270 = 3;

    public function getDegrees(): int
    {
        return $this->getValue() * 90;
    }
}
