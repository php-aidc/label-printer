<?php

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
