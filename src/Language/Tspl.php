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
use PhpAidc\LabelPrinter\Command\InternalImage;
use PhpAidc\LabelPrinter\Command\QRCode;
use PhpAidc\LabelPrinter\Contract\Label;
use PhpAidc\LabelPrinter\Contract\Media;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Enum\Unit;
use PhpAidc\LabelPrinter\Enum\Charset;
use PhpAidc\LabelPrinter\Language\Tspl as Handlers;

final class Tspl implements Language
{
    private const EOC = "\n";

    private const HANDLERS = [
        Raw::class           => Handlers\TsplRaw::class,
        Clear::class         => Handlers\TsplClear::class,
        Bitmap::class        => Handlers\TsplBitmap::class,
        Barcode::class       => Handlers\TsplBarcode::class,
        QRCode::class        => Handlers\TsplQRCode::class,
        TextLine::class      => Handlers\TsplTextLine::class,
        TextBlock::class     => Handlers\TsplTextBlock::class,
        InternalImage::class => Handlers\TsplInternalImage::class,
    ];

    public function compileDeclaration(Label $label): iterable
    {
        yield from $this->translateMedia($label->getMedia());
        yield from $this->translateCharset($label->getCharset());
    }

    public function isSupport(Command $command): bool
    {
        return isset(self::HANDLERS[\get_class($command)]);
    }

    public function compilePrint(int $copies): iterable
    {
        if ($copies <= 0) {
            throw new \InvalidArgumentException('Number of copies must be greather than 0.');
        }

        yield "PRINT 1,{$copies}".self::EOC;
    }

    public function compileCommand(Command $command): iterable
    {
        if ($this->isSupport($command)) {
            $handler = self::HANDLERS[\get_class($command)];

            foreach ((new $handler())->translate($command) as $instruction) {
                yield $instruction.self::EOC;
            }
        } else {
            // throw exception
            yield null;
        }
    }

    private function translateCharset(?Charset $charset): iterable
    {
        if ($charset) {
            yield \sprintf('CODEPAGE %s', $charset->getValue()).self::EOC;
        }
    }

    private function translateMedia(Media $media): iterable
    {
        if ($media->getWidth() && $media->getHeight() === null) {
            yield \sprintf('SIZE %s'.self::EOC, $this->valueWithUnit($media->getWidth(), $media->getUnit()));

            return;
        }

        if ($media->getWidth() && $media->getHeight()) {
            yield \vsprintf('SIZE %s,%s'.self::EOC, [
                $this->valueWithUnit($media->getWidth(), $media->getUnit()),
                $this->valueWithUnit($media->getHeight(), $media->getUnit()),
            ]);
        }
    }

    private function valueWithUnit(float $value, Unit $unit): string
    {
        if ($unit->equals(Unit::IN())) {
            return (string) $value;
        }

        return "{$value} {$unit->getValue()}";
    }
}
