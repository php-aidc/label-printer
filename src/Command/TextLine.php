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
use PhpAidc\LabelPrinter\Command\Concerns\Alignable;
use PhpAidc\LabelPrinter\Command\Concerns\Rotatable;
use PhpAidc\LabelPrinter\Command\Concerns\FontAware;
use PhpAidc\LabelPrinter\Command\Concerns\Invertible;
use PhpAidc\LabelPrinter\Command\Concerns\Magnifiable;
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;
use PhpAidc\LabelPrinter\Command\Concerns\ImageFallback;

final class TextLine implements Command
{
    use Alignable;
    use FontAware;
    use Rotatable;
    use Invertible;
    use Magnifiable;
    use ImageFallback;
    use PositionAware;

    /** @var string */
    private $text;

    /** @var int|null */
    private $maxWidth;

    public function __construct(int $x, int $y, string $text, string $font, $size = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->text = $text;
        $this->fontName = $font;
        $this->fontSize = $size;
    }

    public function maxWidth(int $value)
    {
        $this->maxWidth = $value;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getMaxWidth(): ?int
    {
        return $this->maxWidth;
    }
}
