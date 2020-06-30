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
use PhpAidc\LabelPrinter\Command\Concerns\FontAware;

trait Font
{
    public function font(Command $command)
    {
        /** @var Command|FontAware $command */
        $font = \sprintf('FT "%s"', $command->getFontName());

        if ($command->getFontSize()) {
            $font .= ",".(int) $command->getFontSize();
        }

        yield $font;
    }
}
