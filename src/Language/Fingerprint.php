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

namespace PhpAidc\LabelPrinter\Language;

use PhpAidc\LabelPrinter\Command\Raw;
use PhpAidc\LabelPrinter\Command\Clear;
use PhpAidc\LabelPrinter\Command\Bitmap;
use PhpAidc\LabelPrinter\Command\Barcode;
use PhpAidc\LabelPrinter\Command\TextLine;
use PhpAidc\LabelPrinter\Command\TextBlock;
use PhpAidc\LabelPrinter\Command\ExternalImage;
use PhpAidc\LabelPrinter\Command\InternalImage;
use PhpAidc\LabelPrinter\Contract\Label;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Enum\Unit;
use PhpAidc\LabelPrinter\Enum\Charset;
use PhpAidc\LabelPrinter\Language\Fingerprint as Handlers;

final class Fingerprint implements Language
{
    public const DEFAULT_ANCHOR = 1;
    public const DEFAULT_DIRECTION = 1;
    public const DEFAULT_BARCODE_HEIGHT = 100;
    public const DEFAULT_BARCODE_MAGNIFICATION = 2;

    private const EOC = "\n";

    private const HANDLERS = [
        Raw::class           => Handlers\FingerprintRaw::class,
        Clear::class         => Handlers\FingerprintClear::class,
        Bitmap::class        => Handlers\FingerprintBitmap::class,
        Barcode::class       => Handlers\FingerprintBarcode::class,
        TextLine::class      => Handlers\FingerprintTextLine::class,
        TextBlock::class     => Handlers\FingerprintTextBlock::class,
        ExternalImage::class => Handlers\FingerprintExternalImage::class,
        InternalImage::class => Handlers\FingerprintInternalImage::class,
    ];

    /** @var float */
    private $density;

    public function __construct(float $density)
    {
        $this->density = $density;
    }

    public function compileDeclaration(Label $label): iterable
    {
        yield from $this->translateMedia($label->getMedia());
        yield from $this->translateCharset($label->getCharset());
    }

    public function isSupport(Command $command): bool
    {
        return isset(self::HANDLERS[\get_class($command)]);
    }

    public function compileCommand(Command $command): iterable
    {
        if ($this->isSupport($command)) {
            $class = self::HANDLERS[\get_class($command)];

            $handler = new $class();

            foreach ((new $handler)->translate($command) as $instruction) {
                yield $instruction.self::EOC;
            }
        } else {
            // throw exception
            yield null;
        }
    }

    public function compilePrint(int $copies): iterable
    {
        if ($copies <= 0) {
            throw new \InvalidArgumentException('Number of copies must be greather than 0.');
        }

        yield "PF {$copies}".self::EOC;
    }

    private function translateCharset(?Charset $charset): iterable
    {
        if ($charset) {
            yield \sprintf('NASC "%s"', $charset->getValue()).self::EOC;
        }
    }

    private function translateMedia(array $media): iterable
    {
        ['unit' => $unit, 'width' => $width, 'height' => $height] = $media;

        if ($width) {
            yield \sprintf('SETUP "MEDIA,MEDIA SIZE,WIDTH,%d'.self::EOC, $this->valueToDots($width, $unit));
        }

        if ($height) {
            yield \sprintf('SETUP "MEDIA,MEDIA SIZE,HEIGHT,%d'.self::EOC, $this->valueToDots($height, $unit));
        }
    }

    private function valueToDots(float $value, Unit $unit): int
    {
        if ($unit->equals(Unit::DOT())) {
            return (int) $value;
        }

        if ($unit->equals(Unit::IN())) {
            return (int) \round($this->density * $value);
        }

        return (int) \round(($this->density / 2.54 / 10) * $value);
    }
}
