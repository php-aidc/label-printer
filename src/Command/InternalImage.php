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

use PhpAidc\LabelPrinter\Enum\TsplBpp;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Command\Concerns\Rotatable;
use PhpAidc\LabelPrinter\Command\Concerns\Invertible;
use PhpAidc\LabelPrinter\Command\Concerns\Magnifiable;
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;

/**
 * Prints an image stored in the printer's memory.
 */
final class InternalImage implements Command
{
    use Rotatable;
    use Invertible;
    use Magnifiable;
    use PositionAware;

    /** @var TsplBpp */
    private $bpp;

    /** @var int */
    private $contrast;

    /** @var string */
    private $name;

    public function __construct(int $x, int $y, string $name)
    {
        $this->x = $x;
        $this->y = $y;
        $this->name = $name;
    }

    /**
     * (TSPL) Bits per pixel.
     *
     * @param  TsplBpp  $bpp
     *
     * @return $this
     */
    public function bpp(TsplBpp $bpp)
    {
        $this->bpp = $bpp;

        return $this;
    }

    /**
     * (TSPL) Contrast of grayscale graphic.
     *
     * @param  int  $value
     *
     * @return $this
     */
    public function contrast(int $value)
    {
        $this->contrast = $value;

        return $this;
    }

    public function getBpp(): ?TsplBpp
    {
        return $this->bpp;
    }

    public function getContrast(): ?int
    {
        return $this->contrast;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
