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

use PhpAidc\LabelPrinter\Command\Bitmap;
use PhpAidc\LabelPrinter\Command\TextLine;
use PhpAidc\LabelPrinter\Emulation\EmulateText;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Font;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Align;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Invert;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Rotate;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Magnify;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Position;

final class FingerprintTextLine
{
    use Font;
    use Align;
    use Invert;
    use Rotate;
    use Magnify;
    use Position;
    use EmulateText;

    public function translate(TextLine $command): iterable
    {
        if ($command->shouldEmulate()) {
            $image = $this->emulate($command, $command->getMaxWidth());

            yield from (new FingerprintBitmap())->translate(
                new Bitmap($command->getX(), $command->getY(), $image)
            );

            return;
        }

        yield from $this->position($command);

        yield from $this->rotate($command);

        yield from $this->invert($command);

        yield from $this->align($command);

        yield from $this->font($command);

        yield \sprintf('PT "%s"', $command->getText());

        // reset
        yield from $this->resetAlign($command);
        yield from $this->resetInvert($command);
        yield from $this->resetRotate($command);
        yield from $this->resetMagnify($command);
    }
}
