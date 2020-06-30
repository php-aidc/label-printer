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

final class RllCodec
{
    private const MAX_RUN_LENGTH = 127;
    private const MAX_LINE_REPETITIONS = 128;

    /** @var \Imagick */
    private $image;

    public static function create(\Imagick $image): self
    {
        return new self($image);
    }

    public function __construct(\Imagick $image)
    {
        $this->image = $image;
    }

    public function encode(): string
    {
        return \pack('C*', ...\array_merge(...$this->compressLines($this->scan())));
    }

    private function scan(): \Generator
    {
        foreach ((new \ImagickPixelIterator($this->image)) as $y => $line) {
            $buffer = \array_reduce($line, static function ($carry, $pixel) {
                $color = $pixel->getColor();

                $carry[] = 1 - ((int) (($color['r'] + $color['g'] + $color['b']) / 3) >> 7);

                return $carry;
            }, []);

            yield $this->compressRuns($buffer);
        }
    }

    private function compressRuns(array $line): array
    {
        $prev = null;
        $buffer = [];
        $runLength = 0;
        $size = \count($line);

        foreach ($line as $i => $byte) {
            if ($prev === null || $prev === $byte) {
                $runLength++;
            } else {
                $this->encodeRun($buffer, $runLength);

                $runLength = 1;
            }

            if ($i + 1 === $size) {
                $this->encodeRun($buffer, $runLength);
            }

            $prev = $byte;
        }

        if ($line[0] === 1) {
            \array_unshift($buffer, 0);
        }

        if (\end($line) === 1) {
            $buffer[] = 0;
        }

        return $buffer;
    }

    private function encodeRun(array &$buffer, int $length)
    {
        if ($length < self::MAX_RUN_LENGTH) {
            $buffer[] = \max($length, 1);

            return;
        }

        \array_map(static function () use (&$buffer) {
            $buffer[] = self::MAX_RUN_LENGTH;
            $buffer[] = 0;
        }, \range(1, \intdiv($length, self::MAX_RUN_LENGTH)));

        $buffer[] = $length % self::MAX_RUN_LENGTH;
    }

    private function compressLines(\Generator $lines): array
    {
        $count = 1;
        $line = [];
        $buffer = [];
        $prev = $lines->current();

        $lines->next();

        while ($lines->valid()) {
            $line = $lines->current();

            if ($prev === $line) {
                $count++;

                $prev = $line;

                $lines->next();

                continue;
            }

            $this->encodeLineRepetitions($buffer, $prev, $count);

            $count = 1;

            $prev = $line;

            $lines->next();
        }

        $this->encodeLineRepetitions($buffer, $line, $count);

        return $buffer;
    }

    private function encodeLineRepetitions(array &$buffer, array $line, int $count): void
    {
        if ($count <= 1) {
            $buffer[] = $line;
        } elseif ($count > 1 && $count <= self::MAX_LINE_REPETITIONS) {
            $buffer[] = $this->buildLineRepetition($line, $count);
        } else {
            \array_map(function () use (&$buffer, $line) {
                $buffer[] = $this->buildLineRepetition($line, self::MAX_LINE_REPETITIONS);
            }, \range(1, \intdiv($count, self::MAX_LINE_REPETITIONS)));

            $buffer[] = $this->buildLineRepetition($line, $count % self::MAX_LINE_REPETITIONS);
        }
    }

    private function buildLineRepetition(array $line, int $count): array
    {
        return \array_merge([-$count], $line, [-$count]);
    }
}
