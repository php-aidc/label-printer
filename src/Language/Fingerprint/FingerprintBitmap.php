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
use PhpAidc\LabelPrinter\Emulation\RllCodec;
use PhpAidc\LabelPrinter\Language\Fingerprint\Concerns\Position;

final class FingerprintBitmap
{
    use Position;

    private const PRBUF_HEADER = 0x4002;

    public function translate(Bitmap $command): iterable
    {
        yield from $this->position($command);

        $data = RllCodec::create($command->getCanvas())->encode();

        $stream = \pack('n*', self::PRBUF_HEADER, $command->getWidth(), $command->getHeight()).$data;

        yield \sprintf("PRBUF %d\n", \strlen($stream)).$stream;

    }
}
