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

use PhpAidc\LabelPrinter\Enum\Anchor;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Language\Fingerprint;
use PhpAidc\LabelPrinter\Command\Concerns\Alignable;

trait Align
{
    public function align(Command $command)
    {
        /** @var Command|Alignable $command */
        if ($command->getAnchor() && !$command->getAnchor()->equals(Anchor::SOUTHWEST())) {
            $anchor = $command->getAnchor()->getValue();

            $newAnchor = $anchor + 6;

            if ($newAnchor > 9) {
                $newAnchor = $anchor > 6 ? $anchor - 6 : $anchor;
            }

            yield "AN {$newAnchor}";
        }
    }

    public function resetAlign(Command $command)
    {
        /** @var Command|Alignable $command */
        if ($command->getAnchor() && !$command->getAnchor()->equals(Anchor::SOUTHWEST())) {
            yield 'AN '.Fingerprint::DEFAULT_ANCHOR;
        }
    }
}
