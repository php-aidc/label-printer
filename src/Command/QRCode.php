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

final class QRCode implements Command
{
    use Alignable;
    use Rotatable;
    use PositionAware;

    /** @var int|null */
    private $cellWidth;

    /** @var string */
    private $data;

    /** @var string|null */
    private $eccLevel;

    /** @var int|null */
    private $height;

    /** @var int|null */
    private $magnification;

    /** @var string|null */
    private $mode;

    /** @var string|null */
    private $model;

    public function __construct(int $x, int $y, string $data, string $eccLevel, int $cellWidth, string $mode)
    {
        $this->x = $x;
        $this->y = $y;
        $this->data = $data;
        $this->cellWidth = $cellWidth;
        $this->eccLevel = $eccLevel;
        $this->mode = $mode;        
    }

    public function magnify(int $value)
    {
        $this->magnification = $value;

        return $this;
    }
  
    public function model(string $value)
    {
        $this->model = $value;

        return $this;
    }

    public function getCellWidth(): ?int
    {
        return $this->cellWidth;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getECCLevel(): string
    {
        return $this->eccLevel;
    }

    public function getMagnification(): ?int
    {
        return $this->magnification;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

}
