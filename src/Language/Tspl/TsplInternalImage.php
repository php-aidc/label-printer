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

use PhpAidc\LabelPrinter\Command\InternalImage;

final class TsplInternalImage
{
    public function translate(InternalImage $command): iterable
    {
        if (\preg_match('~\.bmp$~i', $command->getName())) {
            $instruction = \vsprintf('PUTBMP %d,%d,"%s"', [
                $command->getX(),
                $command->getY(),
                $command->getName(),
            ]);

            if ($command->getBpp()) {
                $instruction .= ','.$command->getBpp();
            }

            if ($command->getContrast()) {
                $instruction .= ','.$command->getContrast();
            }

            yield $instruction;

            return;
        }

        if (\preg_match('~\.pcx$~i', $command->getName())) {
            yield \vsprintf('PUTPCX %d,%d,"%s"', [
                $command->getX(),
                $command->getY(),
                $command->getName(),
            ]);

            return;
        }

        throw new \InvalidArgumentException();
    }
}
