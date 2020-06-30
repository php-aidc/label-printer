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

namespace PhpAidc\LabelPrinter\Language\Fingerprint;

use PhpAidc\LabelPrinter\Command\ExternalImage;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Invert;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Rotate;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Position;

final class FingerprintExternalImage
{
    use Invert;
    use Rotate;
    use Position;

    public function translate(ExternalImage $command): iterable
    {
        yield from $this->position($command);

        yield from $this->invert($command);

        yield from $this->rotate($command);

        yield \sprintf("PRBUF %d\n", \strlen($data = $command->getData())).$data;

        // reset
        yield from $this->resetInvert($command);
        yield from $this->resetRotate($command);
    }
}
