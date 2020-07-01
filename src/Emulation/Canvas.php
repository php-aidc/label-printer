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

use PhpAidc\LabelPrinter\Enum\Angle;
use PhpAidc\LabelPrinter\Enum\Anchor;

final class Canvas
{
    /** @var int|null */
    private $width;

    /** @var int|null */
    private $height;

    /** @var \Imagick */
    private $image;

    public function __construct(?int $width = null, ?int $height = null)
    {
        $this->width = $width;
        $this->height = $height;

        $this->image = new \Imagick();
    }

    public function write(
        string $text,
        string $font,
        float $size,
        Angle $rotation,
        ?Anchor $anchor,
        bool $inverted = false,
        ?int $spacing = null
    ): void {
        $canvas = new \ImagickDraw();

        $canvas->setFont($font);
        $canvas->setFontSize($size);
        $canvas->setTextAntialias(true);

        $canvas->setGravity($anchor ? $anchor->getValue() : \Imagick::GRAVITY_NORTHWEST);

        if ($spacing !== null) {
            $canvas->setTextInterLineSpacing($spacing);
        }

        if ($inverted) {
            $foreground = new \ImagickPixel('#FFF');
            $background = new \ImagickPixel('#00000000');

            $canvas->setTextUnderColor(new \ImagickPixel('#000000'));
        } else {
            $foreground = new \ImagickPixel('#000');
            $background = new \ImagickPixel('#FFF');
        }

        $canvas->setFillColor($foreground);

        $metrics = $this->image->queryFontMetrics($canvas, $text, true);

        if ($metrics['textWidth'] > $this->width) {
            [$realWidth, $lines] = $this->splitText($canvas, $text);

            $realWidth = $this->width ?? $realWidth;
            $realHeight = $this->height ?? $metrics['textHeight'] * \count($lines);

            $text = \implode("\n", $lines);
        } else {
            $metrics = $this->image->queryFontMetrics($canvas, $text, false);

            $realWidth = \max($this->width, $metrics['textWidth']);
            $realHeight = \max($this->height, $metrics['textHeight']);
        }

        $canvas->annotation(0, 0, $text);

        $this->image->newImage((int) $realWidth, (int) $realHeight, $background);

        $this->image->drawImage($canvas);

        if ($rotation->getDegrees()) {
            $this->image->rotateImage($background, $rotation->getDegrees());
        }
    }

    public function toImage(): \Imagick
    {
        $this->image->setImageFormat('png');

        $this->image->setImageType(\Imagick::IMGTYPE_GRAYSCALE);

        return $this->image;
    }

    private function splitText(\ImagickDraw $canvas, string $text): array
    {
        $words = \preg_split('~\s~u', \trim($text), -1, \PREG_SPLIT_NO_EMPTY);

        if (empty($words)) {
            return [
                $this->image->queryFontMetrics($canvas, $text)['textWidth'],
                [$text],
            ];
        }

        $i = 1;
        $lines = [];
        $lineWidth = [];

        while(\count($words) > 0) {
            $metrics = $this->image->queryFontMetrics($canvas, \implode(' ', \array_slice($words, 0, $i)));

            if ($metrics['textWidth'] > $this->width || \count($words) < $i) {
                $lineWidth[] = $metrics['textWidth'];

                $lines[] = \implode(' ', \array_slice($words, 0, \max($i - 1, 1)));

                $words = \array_slice($words, \max($i - 1, 1));

                $i = 0;
            }

            $i++;
        }

        return [\max($lineWidth), $lines];
    }
}
