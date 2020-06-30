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

use PhpAidc\LabelPrinter\Command\InternalImage;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Invert;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Rotate;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Magnify;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Position;

final class FingerprintInternalImage
{
    use Invert;
    use Rotate;
    use Magnify;
    use Position;

    public function translate(InternalImage $command): iterable
    {
        yield $this->position($command);

        yield $this->rotate($command);

        yield $this->magnify($command);

        yield "PM {$command->getName()}";

        // reset
        yield from $this->resetInvert($command);
        yield from $this->resetRotate($command);
        yield from $this->resetMagnify($command);
    }
}
