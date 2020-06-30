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

namespace PhpAidc\LabelPrinter\Emulation;

use PhpAidc\LabelPrinter\Command\TextLine;
use PhpAidc\LabelPrinter\Command\TextBlock;

trait EmulateText
{
    /**
     * @param  TextLine|TextBlock  $command
     * @param  int|null            $width
     * @param  int|null            $height
     * @param  int|null            $spacing
     *
     * @return \Imagick
     */
    private function emulate($command, ?int $width = null, ?int $height = null, ?int $spacing = null): \Imagick
    {
        $canvas = new Canvas($width, $height);

        $canvas->write(
            $command->getText(),
            $command->getFontName(),
            (float) $command->getFontSize(),
            $command->getRotation(),
            $command->getAnchor(),
            $command->isInverted(),
            $spacing
        );

        return $canvas->toImage();
    }
}
