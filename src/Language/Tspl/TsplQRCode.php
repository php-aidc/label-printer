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

use PhpAidc\LabelPrinter\Command\QRCode;

final class TsplQRCode
{
    public function translate(QRCode $command): iterable
    {
        $instruction = \vsprintf('QRCODE %d,%d,%s,%d,%s', [
            $command->getX(),
            $command->getY(),
            $command->getECCLevel(),
            $command->getCellWidth(),
            $command->getMode()
        ]);

        // rotation
        $instruction .= ','.$command->getRotation()->getDegrees();

        // model
        if ($command->getModel()) {
            $instruction .= ','.(string) $command->getModel();
        }
     
        yield $instruction.\sprintf(',"%s"', $command->getData());
    }
}
