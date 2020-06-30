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

use PhpAidc\LabelPrinter\Enum\Angle;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Language\Fingerprint;
use PhpAidc\LabelPrinter\Command\Concerns\Rotatable;

trait Rotate
{
    public function rotate(Command $command)
    {
        /** @var Command|Rotatable $command */
        if (!$command->getRotation()->equals(Angle::_0())) {
            yield \sprintf('DIR %d', $command->getRotation()->getValue() + 1);
        }
    }

    public function resetRotate(Command $command)
    {
        /** @var Command|Rotatable $command */
        if (!$command->getRotation()->equals(Angle::_0())) {
            yield 'DIR '.Fingerprint::DEFAULT_DIRECTION;
        }
    }
}
