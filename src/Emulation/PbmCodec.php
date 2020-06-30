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

namespace PhpAidc\LabelPrinter\Emulation;

final class PbmCodec
{
    /** @var \Imagick */
    private $image;

    /** @var array */
    private $buffer;

    /** @var int */
    private $bytesPerRow;

    public static function create(\Imagick $image): self
    {
        return new self($image);
    }

    public function __construct(\Imagick $image)
    {
        $this->image = $image;

        $this->bytesPerRow = \intdiv($image->getImageWidth() + 7, 8);

        $this->buffer = \array_fill(0, $this->bytesPerRow * $image->getImageHeight(), 0);
    }

    public function encode(): string
    {
        foreach ((new \ImagickPixelIterator($this->image)) as $y => $row) {
            $bit = $byte = 0;

            /** @var \ImagickPixel[] $row */
            foreach ($row as $x => $pixel) {
                $color = $pixel->getColor();

                $value = (int) (($color['r'] + $color['g'] + $color['b']) / 3) >> 7;

                $bit = $x % 8;

                $byte = ($y * $this->bytesPerRow) + \intdiv($x, 8);

                if ($value === 0) {
                    $this->buffer[$byte] &= ~(1 << (7 - $bit));
                } else {
                    $this->buffer[$byte] |= (1 << (7 - $bit));
                }
            }

            if ($bit !== 7) {
                $this->buffer[$byte] ^= (1 << (7 - $bit)) - 1;
            }
        }

        return \pack('C*', ...$this->buffer);
    }
}
