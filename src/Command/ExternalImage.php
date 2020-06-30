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
use PhpAidc\LabelPrinter\Command\Concerns\Rotatable;
use PhpAidc\LabelPrinter\Command\Concerns\Invertible;
use PhpAidc\LabelPrinter\Command\Concerns\PositionAware;

final class ExternalImage implements Command
{
    use Rotatable;
    use Invertible;
    use PositionAware;

    private $filename;

    /**
     * @param  int                  $x
     * @param  int                  $y
     * @param  \SplFileInfo|string  $filename
     */
    public function __construct(int $x, int $y, $filename)
    {
        $this->x = $x;
        $this->y = $y;
        $this->filename = $filename;
    }

    public function getData(): string
    {
        return $this->read($this->filename);
    }

    private function read($data): string
    {
        if (\is_string($data)) {
            $data = new \SplFileInfo($data);
        }

        if ($data instanceof \SplFileInfo) {
            if (!$data->isReadable()) {
                throw new \RuntimeException();
            }

            return \file_get_contents($data->getRealPath());
        }

        throw new \InvalidArgumentException();
    }
}
