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

use PhpAidc\LabelPrinter\Enum\Unit;
use PhpAidc\LabelPrinter\Enum\Charset;
use PhpAidc\LabelPrinter\Enum\Direction;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;

final class Label implements LabelContract
{
    private $media;

    private $commands = [];

    /** @var int */
    private $copies = 1;

    /** @var Charset|null */
    private $charset;

    /** @var Direction|null */
    private $direction;

    public static function create(?Unit $unit = null, ?float $width = null, ?float $height = null): self
    {
        return new self($unit, $width, $height);
    }

    public function __construct(?Unit $unit = null, ?float $width = null, ?float $height = null)
    {
        if ($unit === null && ($width !== null || $height !== null)) {
            throw new \InvalidArgumentException();
        }

        $this->media = \compact('unit', 'width', 'height');
    }

    public function add(Command $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    public function direction(Direction $value)
    {
        $this->direction = $value;

        return $this;
    }

    public function charset(Charset $value)
    {
        $this->charset = $value;

        return $this;
    }

    public function copies(int $copies)
    {
        $this->copies = $copies;

        return $this;
    }

    public function getCopies(): int
    {
        return $this->copies;
    }

    public function getCharset(): ?Charset
    {
        return $this->charset;
    }

    public function getMedia(): array
    {
        return $this->media;
    }

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function getWidth(): ?float
    {
        return $this->media['width'] ?? null;
    }

    public function getHeight(): ?float
    {
        return $this->media['height'] ?? null;
    }

    public function getCommands(string $language): iterable
    {
        foreach ($this->commands as $command) {
            if ($command instanceof Condition) {
                if ($command->isMatch($language)) {
                    foreach ($command->call((clone $this)->erase()) as $item) {
                        yield $item;
                    }
                }
            } else {
                yield $command;
            }
        }
    }

    public function erase()
    {
        $this->commands = [];

        return $this;
    }

    public function when(string $language, \Closure $closure)
    {
        $this->commands[] = new Condition($language, $closure);

        return $this;
    }
}
