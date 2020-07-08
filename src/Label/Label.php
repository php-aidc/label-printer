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
use PhpAidc\LabelPrinter\Contract\Condition;
use PhpAidc\LabelPrinter\Contract\Media as MediaContract;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;

final class Label implements LabelContract
{
    /** @var int */
    private $copies = 1;

    /** @var Charset|null */
    private $charset;

    /** @var Direction|null */
    private $direction;

    /** @var Media */
    private $media;

    /** @var Command[]|Condition[] */
    private $statements = [];

    public static function create(?Unit $unit = null, ?float $width = null, ?float $height = null): self
    {
        return new self($unit, $width, $height);
    }

    public function __construct(?Unit $unit = null, ?float $width = null, ?float $height = null)
    {
        if ($unit === null && ($width !== null || $height !== null)) {
            throw new \InvalidArgumentException();
        }

        $this->media = new Media($unit, $width, $height);
    }

    public function add(Command $command)
    {
        $this->statements[] = $command;

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

    public function direction(Direction $value)
    {
        $this->direction = $value;

        return $this;
    }

    public function getCharset(): ?Charset
    {
        return $this->charset;
    }

    public function getCopies(): int
    {
        return $this->copies;
    }

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function getMedia(): MediaContract
    {
        return $this->media;
    }

    public function getWidth(): ?float
    {
        return $this->media['width'] ?? null;
    }

    public function getHeight(): ?float
    {
        return $this->media['height'] ?? null;
    }

    public function erase()
    {
        $this->statements = [];

        return $this;
    }

    /**
     * Apply the callback only if the languages match.
     *
     * @param  string    $language
     * @param  callable  $callback
     *
     * @return $this
     */
    public function for(string $language, callable $callback)
    {
        $this->statements[] = new LanguageCondition($language, $callback);

        return $this;
    }

    /**
     * Apply the callback if the value is truthy.
     *
     * @param  mixed     $value
     * @param  callable  $callback
     *
     * @return $this
     */
    public function when($value, callable $callback)
    {
        $this->statements[] = new BooleanCondition($value, $callback);

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->statements);
    }
}
