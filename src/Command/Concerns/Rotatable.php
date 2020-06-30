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

namespace PhpAidc\LabelPrinter\Command\Concerns;

use PhpAidc\LabelPrinter\Enum\Angle;

trait Rotatable
{
    /** @var Angle|null */
    private $angle;

    public function rotate(Angle $angle)
    {
        $this->angle = $angle;

        return $this;
    }

    public function getRotation(): Angle
    {
        return $this->angle ?? Angle::_0();
    }
}
