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
use PhpAidc\LabelPrinter\Command\Concerns\Invertible;

trait Invert
{
    public function invert(Command $command)
    {
        /** @var Command|Invertible $command */
        if ($command->isInverted()) {
            yield 'II';
        }
    }

    public function resetInvert(Command $command)
    {
        /** @var Command|Invertible $command */
        if ($command->isInverted()) {
            yield 'NI';
        }
    }
}
