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
use PhpAidc\LabelPrinter\Emulation\PbmCodec;

final class TsplBitmap
{
    public function translate(Bitmap $command): iterable
    {
        yield \vsprintf('BITMAP %d,%d,%d,%d,0,', [
            $command->getX(),
            $command->getY(),
            (int) \ceil($command->getWidth() / 8),
            $command->getHeight(),
        ]).PbmCodec::create($command->getCanvas())->encode();
    }
}
