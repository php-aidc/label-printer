<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 *  © Appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Language\Fingerprint\Concerns;

use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Command\Concerns\Magnifiable;

trait Magnify
{
    public function magnify(Command $command)
    {
        /** @var Command|Magnifiable $command */
        if ($command->getMagnificationWidth() > 1 || $command->getMagnificationHeight() > 1) {
            yield "MAG {$command->getMagnificationHeight()},{$command->getMagnificationWidth()}";
        }
    }

    public function resetMagnify(Command $command)
    {
        /** @var Command|Magnifiable $command */
        if ($command->getMagnificationWidth() > 1 || $command->getMagnificationHeight() > 1) {
            yield \sprintf('MAG %d,%d', 1, 1);
        }
    }
}
