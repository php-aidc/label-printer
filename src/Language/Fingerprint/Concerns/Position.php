<?php

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Language\Fingerprint\Concerns;

use PhpAidc\LabelPrinter\Contract\Command;

trait Position
{
    public function position(Command $command)
    {
        yield "PP {$command->getX()},{$command->getY()}";
    }
}
