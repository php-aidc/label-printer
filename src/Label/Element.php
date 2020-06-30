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

namespace PhpAidc\LabelPrinter\Label;

use PhpAidc\LabelPrinter\Command\Raw;
use PhpAidc\LabelPrinter\Command\Clear;
use PhpAidc\LabelPrinter\Command\Bitmap;
use PhpAidc\LabelPrinter\Command\Barcode;
use PhpAidc\LabelPrinter\Command\TextLine;
use PhpAidc\LabelPrinter\Command\TextBlock;
use PhpAidc\LabelPrinter\Command\ExternalImage;
use PhpAidc\LabelPrinter\Command\InternalImage;

final class Element
{
    /**
     * Sends raw command to the printer.
     *
     * @param  iterable|string  $data
     *
     * @return Raw
     */
    public static function raw($data): Raw
    {
        return new Raw($data);
    }

    /**
     * Partially or completely clears the print image buffer.
     *
     * @param  string|null  $field  the field from which the print image buffer
     *                              should be cleared (Fingerprint only)
     *
     * @return Clear
     */
    public static function clear(?string $field = null): Clear
    {
        return new Clear($field);
    }

    public static function bitmap(int $x, int $y, \Imagick $canvas): Bitmap
    {
        return new Bitmap(...\func_get_args());
    }

    /**
     * Prints an image from host's filesystem.
     *
     * @param  int                  $x
     * @param  int                  $y
     * @param  \SplFileInfo|string  $source
     *
     * @return ExternalImage
     */
    public static function extImage(int $x, int $y, $source): ExternalImage
    {
        return new ExternalImage(...\func_get_args());
    }

    /**
     * Prints an image stored in the printer's memory.
     *
     * @param  int     $x
     * @param  int     $y
     * @param  string  $name  full name of the image
     *
     * @return InternalImage
     */
    public static function intImage(int $x, int $y, string $name): InternalImage
    {
        return new InternalImage(...\func_get_args());
    }

    public static function barcode(int $x, int $y, string $data, string $type): Barcode
    {
        return new Barcode(...\func_get_args());
    }

    public static function textLine(int $x, int $y, string $text, string $font, $size = null): TextLine
    {
        return new TextLine(...\func_get_args());
    }

    public static function textBlock(int $x, int $y, string $text, string $font, float $size = null): TextBlock
    {
        return new TextBlock(...\func_get_args());
    }
}
