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
use PhpAidc\LabelPrinter\Command\TextLine;
use PhpAidc\LabelPrinter\Emulation\EmulateText;

final class TsplTextLine
{
    use EmulateText;

    public function translate(TextLine $command): iterable
    {
        if ($command->shouldEmulate()) {
            $image = $this->emulate($command, $command->getMaxWidth());

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

        $format = ['%d', '%d', '"%s"', '%d', $specifier, $specifier];

        // common parameters
        $values = [
            $command->getX(),
            $command->getY(),
            $command->getFontName(),
            $command->getRotation()->getDegrees(),
            $size,
            $size,
        ];

        // Rongta RP 4xx/Atol BP4x  doesn't support alignment and print void
        if ($command->getAnchor()) {
            $format[] = '%d';
            $values[] = (($command->getAnchor()->getValue() % 3) ?: 3);
        }

        // text
        $format[] = '"%s"';
        $values[] = \str_replace('"', '\["]', $command->getText());

        yield \vsprintf('TEXT '.\implode(',', $format), $values);
    }
}
