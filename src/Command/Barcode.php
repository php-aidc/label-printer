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
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;

final class Barcode implements Command
{
    use Alignable;
    use Rotatable;
    use PositionAware;

    /** @var string */
    private $data;

    /** @var string */
    private $type;

    /** @var int|null */
    private $height;

    /** @var int|null */
    private $magnification;

    /** @var array */
    private $ratio = ['narrow' => 1, 'wide' => 1];

    /** @var bool */
    private $readable = false;

    public function __construct(int $x, int $y, string $data, string $type)
    {
        $this->x = $x;
        $this->y = $y;
        $this->data = $data;
        $this->type = $type;
    }

    public function height(int $value)
    {
        $this->height = $value;

        return $this;
    }

    public function magnify(int $value)
    {
        $this->magnification = $value;

        return $this;
    }

    public function ratio(int $narrow, int $wide)
    {
        $this->ratio = \compact('narrow', 'wide');

        return $this;
    }

    public function readable(bool $readable = true)
    {
        $this->readable = $readable;

        return $this;
    }
    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getMagnification(): ?int
    {
        return $this->magnification;
    }

    public function getRatio(): array
    {
        return $this->ratio;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }
}
