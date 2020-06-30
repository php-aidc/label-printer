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

namespace PhpAidc\LabelPrinter\Command\Concerns;

trait Magnifiable
{
    /** @var int */
    private $magnificationWidth = 1;
    private $magnificationHeight = 1;

    public function magnify(int $width, $height)
    {
        $this->magnificationWidth = $width;
        $this->magnificationHeight = $height;

        return $this;
    }

    public function getMagnificationWidth(): int
    {
        return $this->magnificationWidth;
    }

    public function getMagnificationHeight(): int
    {
        return $this->magnificationHeight;
    }
}
