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

namespace PhpAidc\LabelPrinter\Language\Tspl;

use PhpAidc\LabelPrinter\Command\Bitmap;
use PhpAidc\LabelPrinter\Command\TextBlock;
use PhpAidc\LabelPrinter\Emulation\EmulateText;

/**
 * This printers doesn't support BLOCK:
 *  - Atol BP4x
 *  - Rongta RP4xx
 */
final class TsplTextBlock
{
    use EmulateText;

    public function translate(TextBlock $command): iterable
    {
        if ($command->shouldEmulate()) {
            $image = $this->emulate(
                $command,
                $command->getBoxWidth(),
                $command->getBoxHeight(),
                $command->getSpacing()
            );

            yield from (new TsplBitmap())->translate(
                new Bitmap($command->getX(), $command->getY(), $image)
            );

            return;
        }

        $size = $command->getFontSize();

        if (\is_string($size)) {
            $size += 0;
        }

        if (empty($size)) {
            $size = 1;
        }

        $specifier = \is_float($size) ? '%.2F' : '%d';

        $format = "%d,%d,%d,%d,\"%s\",%d,{$specifier},{$specifier},0,%d,0,\"%s\"";

        yield \vsprintf('BLOCK '.$format, [
            $command->getX(),
            $command->getY(),
            $command->getBoxWidth(),
            $command->getBoxHeight(),
            $command->getFontName(),
            $command->getRotation()->getDegrees(),
            $size,
            $size,
            $command->getSpacing() ?? 0,
            $command->getAnchor() ? $command->getAnchor()->getValue() : 0,
            \str_replace(['"', "\n", "\r"], ['\["]', '\[L]', '\[R]'], $command->getText()),
        ]);
    }
}
