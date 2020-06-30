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

use PhpAidc\LabelPrinter\Command\Barcode;

final class TsplBarcode
{
    public function translate(Barcode $command): iterable
    {
        $instruction = \vsprintf('BARCODE %d,%d,"%s",%d', [
            $command->getX(),
            $command->getY(),
            $command->getType(),
            $command->getHeight(),
        ]);

        // human readable
        $instruction .= ','.(int) $command->isReadable();

        // rotation
        $instruction .= ','.$command->getRotation()->getDegrees();

        // width of narrow element
        $instruction .= ','.(int) $command->getRatio()['narrow'];

        // width of wide element
        $instruction .= ','.(int) $command->getRatio()['wide'];

        // alingment (Rongta RP 4xx/Atol BP4x  doesn't support alignment and prints void)
        if ($command->getAnchor()) {
            $instruction .= ','.$command->getAnchor()->getValue();
        }

        yield $instruction.\sprintf(',"%s"', $command->getData());
    }
}
