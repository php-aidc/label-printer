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

use PhpAidc\LabelPrinter\Command\Barcode;
use PhpAidc\LabelPrinter\Language\Fingerprint;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Font;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Align;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Rotate;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Position;

final class FingerprintBarcode
{
    use Font;
    use Align;
    use Rotate;
    use Position;

    public function translate(Barcode $command): iterable
    {
        yield from $this->position($command);

        yield from $this->rotate($command);

        yield from $this->align($command);

        yield \sprintf('BT "%s"', $command->getType());

        // TODO: ratio
        //yield \sprintf('BR %d,%d', $ratio['wide'], $ratio['narrow']);

        $magnification = $command->getMagnification();

        if ($magnification > 0 && $magnification !== Fingerprint::DEFAULT_BARCODE_MAGNIFICATION) {
            yield "BM {$command->getMagnification()}";
        }

        if ($command->getHeight() > 0) {
            yield \sprintf('BH %d', $command->getHeight());
        }

        yield 'BF '.($command->isReadable() ? 'ON' : 'OFF');

        yield \sprintf('PB "%s"', $command->getData());

        // reset
        yield from $this->resetAlign($command);
        yield from $this->resetRotate($command);

        if ($command->getHeight() !== Fingerprint::DEFAULT_BARCODE_HEIGHT) {
            yield \sprintf('BH %d', Fingerprint::DEFAULT_BARCODE_HEIGHT);
        }

        if ($magnification > 0 && $magnification !== Fingerprint::DEFAULT_BARCODE_MAGNIFICATION) {
            yield \sprintf('BM %d', Fingerprint::DEFAULT_BARCODE_MAGNIFICATION);
        }

        if (! $command->isReadable()) {
            yield 'BF ON';
        }
    }
}
