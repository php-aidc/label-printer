<?php

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Language\Fingerprint\Concerns;

use PhpAidc\LabelPrinter\Contract\Command;

trait Font
{
    public function font(Command $command)
    {
        $font = \sprintf('FT "%s"', $command->getFontName());

        if ($command->getFontSize()) {
            $font .= ",".(int) $command->getFontSize();
        }

        yield $font;
    }
}
