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

use PhpAidc\LabelPrinter\Command\Concerns\Invertible;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Command\Concerns\Alignable;
use PhpAidc\LabelPrinter\Command\Concerns\Rotatable;
use PhpAidc\LabelPrinter\Command\Concerns\FontAware;
use PhpAidc\LabelPrinter\Command\Concerns\Magnifiable;
use PhpAidc\LabelPrinter\Command\Concerns\ImageFallback;
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;

final class TextBlock implements Command
{
    use Alignable;
    use Rotatable;
    use FontAware;
    use Magnifiable;
    use Invertible;
    use PositionAware;
    use ImageFallback;

    /** @var string */
    private $text;

    /** @var int|null */
    private $spacing;

    private $box = ['width' => null, 'height' => null, 'border' => 0];

    public function __construct(int $x, int $y, string $text, string $font, float $size = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->text = $text;
        $this->fontName = $font;
        $this->fontSize = $size;
    }

    public function box(int $width, int $height, int $border = 0)
    {
        $this->box = \compact('width', 'height', 'border');

        return $this;
    }

    public function spacing(int $dots)
    {
        $this->spacing = $dots;

        return $this;
    }

    public function getBoxBorder(): int
    {
        return $this->box['border'];
    }

    public function getBoxWidth(): ?int
    {
        return $this->box['width'];
    }

    public function getBoxHeight(): ?int
    {
        return $this->box['height'];
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getSpacing(): ?int
    {
        return $this->spacing;
    }
}
