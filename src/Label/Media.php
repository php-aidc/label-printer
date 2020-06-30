<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 *  © Appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Label;

use PhpAidc\LabelPrinter\Enum\Unit;

final class Media
{
    /** @var Unit|null */
    private $unit;

    /** @var float|null */
    private $width;

    /** @var float|null */
    private $height;

    public function __construct(?Unit $unit = null, ?float $width = null, ?float $height = null)
    {
        $this->unit = $unit;
        $this->width = $width;
        $this->height = $height;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }
}
