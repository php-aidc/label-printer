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
 * @method static UTF8()
 * @method static W1251()
 * @method static W1252()
 */
final class Charset extends Enum
{
    private const UTF8  = 'UTF-8';
    private const W1251 = '1251';
    private const W1252 = '1252';
}
