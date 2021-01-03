<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 * © Appwilio (https://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpAidc\LabelPrinter\Contract;

use PhpAidc\LabelPrinter\Enum\Unit;

interface Media
{
    public function getUnit(): ?Unit;

    public function getWidth(): ?float;

    public function getHeight(): ?float;
}
