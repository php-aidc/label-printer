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

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Language\Tspl;

use PhpAidc\LabelPrinter\Command\Clear;

final class TsplClear
{
    public function translate(Clear $command): iterable
    {
        yield 'CLS';
    }
}
