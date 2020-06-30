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

namespace PhpAidc\LabelPrinter\Command;

use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;

final class Bitmap implements Command
{
    use PositionAware;

    private $canvas;

    /** @var bool */
    private $overlay;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    public function __construct(int $x, int $y, \Imagick $canvas)
    {
        $this->x = $x;
        $this->y = $y;
        $this->canvas = clone $canvas;
        $this->width = $this->canvas->getImageWidth();
        $this->height = $this->canvas->getImageHeight();

        $this->canvas->setImageType(\Imagick::IMGTYPE_TRUECOLOR);
        $this->canvas->thresholdImage(.5 * \Imagick::getQuantumRange()['quantumRangeLong']);
    }

    public function overlay($value)
    {
        $this->overlay = $value;

        return $this;
    }

    public function getOverlay()
    {
        return $this->overlay;
    }

    public function getCanvas()
    {
        return $this->canvas;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
